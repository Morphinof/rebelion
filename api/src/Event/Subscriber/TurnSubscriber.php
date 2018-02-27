<?php
/**
 * Created by PhpStorm.
 * User: Morphinof
 * Date: 04/02/2018
 * Time: 09:14
 */

namespace Rebelion\Event\Subscriber;

use Rebelion\Entity\Container\ProxyCard;
use Rebelion\Entity\Turn;
use Rebelion\Enum\CardStatusEnum;
use Rebelion\Enum\CharacteristicEnum;
use Rebelion\Enum\CombatPhasesEnum;
use Rebelion\Enum\TurnPhasesEnum;
use Rebelion\Event\Combat\DrawPileIsEmpty;
use Rebelion\Event\Combat\NextTurn;
use Rebelion\Event\Combat\Turn\Draw;
use Rebelion\Event\Combat\Turn\End;
use Rebelion\Event\Combat\Turn\Init;
use Rebelion\Event\Combat\Turn\Main;
use Rebelion\Service\CombatService;
use Rebelion\Service\TurnService;
use Psr\Log\LoggerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class TurnSubscriber implements EventSubscriberInterface
{
    /** @var EntityManagerInterface */
    private $em;

    /** @var */
    private $logger;

    /** @var EventDispatcherInterface $dispatcher */
    private $dispatcher;

    /** @var CombatService $combatService */
    private $combatService;

    /** @var TurnService $turnService */
    private $turnService;

    /**
     * CardPlayedSubscriber constructor.
     *
     * @param EntityManagerInterface   $entityManager
     * @param LoggerInterface          $logger
     * @param EventDispatcherInterface $dispatcher
     * @param CombatService            $combatService
     * @param TurnService              $turnService
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        LoggerInterface $logger,
        EventDispatcherInterface $dispatcher,
        CombatService $combatService,
        TurnService $turnService
    ) {
        $this->em            = $entityManager;
        $this->logger        = $logger;
        $this->dispatcher    = $dispatcher;
        $this->combatService = $combatService;
        $this->turnService   = $turnService;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            Draw::NAME => [
                ['onDraw', 0],
            ],
            End::NAME  => [
                ['onEnd', 0],
            ],
            Init::NAME => [
                ['onInit', 0],
            ],
            Main::NAME => [
                ['onMain', 0],
            ],
        ];
    }

    /**
     * Turn Event
     *
     * @param Draw $event
     */
    public function onDraw(Draw $event)
    {
        # Current turn player draws his hand size cards
        $turn   = $event->getTurn();
        $player = $turn->getPlayer();

        $handSize = $player->getPlayer()->getCharacteristics()->getHandSize();
        $currentHandSize = $player->getHand()->getCards()->count();
        if ($handSize > $currentHandSize) {
            for ($i = $currentHandSize; $i < $handSize; $i++) {
                $card = $player->getDraw()->draw();

                if ($card === null) {
                    $draw   = $player->getDraw();
                    $combat = $turn->getCombat();
                    $event  = new DrawPileIsEmpty($combat, $draw);
                    $this->dispatcher->dispatch(DrawPileIsEmpty::NAME, $event);
                    $i--;
                    continue;
                }

                $player->getHand()->pushCard($card);
            }

            $this->em->persist($player);
            $this->em->flush();

            $message = sprintf(
                'Combat #%s - Turn #%s - Player %s has drawn %s cards (%s)',
                $turn->getCombat()->getId(),
                $turn->getId(),
                $player->getPlayer()->getId(),
                $handSize,
                implode(', ', $player->getHand()->getCards()->toArray())
            );
            $this->logger->info($message);
        }
    }

    /**
     * Turn Event
     *
     * @param End $event
     */
    public function onEnd(End $event)
    {
        $turn   = $event->getTurn();
        $combat = $turn->getCombat();
        $player = $combat->getCurrentTurn()->getPlayer();

        # Ending the current combat turn
        # Moving non playable hand cards to discard pile
        $hand    = $player->getHand();
        $discard = $player->getDiscard();

        /** @var ProxyCard $proxy */
        foreach ($hand->getCards() as $proxy) {
            if ($proxy->getStatus() !== CardStatusEnum::PLAYABLE) {
                $hand->removeCard($proxy);
                $discard->pushCard($proxy);
            }
        }

        # Create the next turn
        /** @var Turn $turn */
        $currentTurn = $combat->getCurrentTurn();

        $turn = new Turn();
        $turn->setCombat($combat);
        $turn->setPhase(TurnPhasesEnum::INIT);
        $turn->setPlayer($currentTurn->getAdversary());
        $turn->setAdversary($currentTurn->getPlayer());

        $combat->addTurn($turn);
        $combat->setPhase(CombatPhasesEnum::START_TURN);

        $this->em->persist($combat);
        $this->em->flush();

        $event = new NextTurn($combat);
        $this->dispatcher->dispatch(NextTurn::NAME, $event);
    }

    /**
     * Turn Event
     *
     * @param Init $event
     */
    public function onInit(Init $event)
    {
        # Resets turn player characteristics
        $turn   = $event->getTurn();
        $player = $turn->getPlayer()->getPlayer();

        $basePlayerAP = $player->getRace()->getCharacteristics()->getAp();
        $player->getCharacteristics()->setCharacteristic(CharacteristicEnum::AP, $basePlayerAP);
        $player->getCharacteristics()->setCharacteristic(CharacteristicEnum::DEFENCE, 0);

        $this->em->persist($player);
        $this->em->flush();

        $message = sprintf(
            'Combat #%s - Turn #%s - Initialised',
            $turn->getCombat()->getId(),
            $turn->getId()
        );
        $this->logger->info($message);
    }

    /**
     * Turn Event
     *
     * @param Main $event
     */
    public function onMain(Main $event)
    {
    }
}