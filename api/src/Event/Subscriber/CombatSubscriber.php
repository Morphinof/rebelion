<?php
/**
 * Created by PhpStorm.
 * User: Morphinof
 * Date: 04/02/2018
 * Time: 09:14
 */

namespace Rebelion\Event\Subscriber;

use Psr\Log\LoggerInterface;
use Rebelion\Entity\Combat;
use Rebelion\Entity\Container\ProxyCard;
use Rebelion\Entity\Effect\ProxyEffect;
use Rebelion\Entity\Pile\Discard;
use Rebelion\Entity\Pile\Hand;
use Rebelion\Entity\Target;
use Rebelion\Entity\Turn;
use Rebelion\Enum\CombatPhasesEnum;
use Rebelion\Enum\TurnPhasesEnum;
use Rebelion\Event\Combat\DeadTarget;
use Rebelion\Event\Combat\DrawPileIsEmpty;
use Rebelion\Event\Combat\End;
use Rebelion\Event\Combat\Init as CombatInit;
use Rebelion\Event\Combat\Lose as CombatLose;
use Rebelion\Event\Combat\NextTurn;
use Rebelion\Event\Combat\PlayTurn;
use Rebelion\Event\Combat\ProxyCardDiscarded;
use Rebelion\Event\Combat\ProxyCardPlayed;
use Rebelion\Event\Combat\ProxyEffectResolved;
use Rebelion\Event\Combat\StartTurn;
use Doctrine\ORM\EntityManagerInterface;
use Rebelion\Event\Combat\Win as CombatWin;
use Rebelion\Service\CombatService;
use Rebelion\Service\TurnService;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CombatSubscriber implements EventSubscriberInterface
{
    /** @var EntityManagerInterface */
    private $em;

    /** @var LoggerInterface $logger */
    private $logger;

    /** @var EventDispatcherInterface $logger */
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
            DeadTarget::NAME          => [
                ['onDeadTarget', 0],
            ],
            DrawPileIsEmpty::NAME     => [
                ['onEmptyDrawPile', 0],
            ],
            End::NAME                 => [
                ['onEnd', 0]
            ],
            CombatInit::NAME          => [
                ['onCombatInit', 0]
            ],
            CombatLose::NAME          => [
                ['onLose', 0]
            ],
            NextTurn::NAME            => [
                ['onNextTurn', 0]
            ],
            PlayTurn::NAME            => [
                ['onPlayTurn', 0]
            ],
            ProxyCardPlayed::NAME     => [
                ['onProxyCardPlayed', 0],
            ],
            ProxyCardDiscarded::NAME  => [
                ['onProxyCardDiscarded', 0],
            ],
            ProxyEffectResolved::NAME => [
                ['onProxyEffectResolved', 0],
            ],
            StartTurn::NAME           => [
                ['onStartTurn', 0]
            ],
            CombatWin::NAME           => [
                ['onWin', 0]
            ],
        ];
    }

    /**
     * Combat Event
     *
     * @param DeadTarget $event
     */
    public function onDeadTarget(DeadTarget $event)
    {
        $combat = $event->getCombat();
        $target = $event->getTarget();
        $target->setDead(true);

        $this->em->persist($target);
        $this->em->flush();

        if ($combat->getPlayer() === $target) {
            $event = new CombatLose($combat, $target);
            $this->dispatcher->dispatch(CombatLose::NAME, $event);
        } else {
            if ($combat->getAdversary() === $target) {
                $event = new CombatWin($combat, $target);
                $this->dispatcher->dispatch(CombatWin::NAME, $event);
            } else {
                # TODO : Minions
            }
        }
    }

    /**
     * Combat Event
     *
     * @param DrawPileIsEmpty $event
     */
    public function onEmptyDrawPile(DrawPileIsEmpty $event)
    {
        $combat = $event->getCombat();
        $draw   = $event->getDraw();

        $message = sprintf('Combat #%s Draw pile #%s is empty', $combat->getId(), $draw->getId());
        $this->logger->info($message);

        # Draw pile is empty, moving discard pile into draw pile
        $discard = $combat->getCurrentTurn()->getPlayer()->getDiscard();

        $positions = [];
        for ($i = 0; $i < $discard->getCards()->count(); $i++) {
            $positions[] = $i;
        }
        shuffle($positions);

        /** @var ProxyCard $proxy */
        foreach ($discard->getCards() as $index => $proxy) {
            $draw->pushCard($proxy, $positions[$index]);
            $discard->removeCard($proxy);
        }

        $this->em->persist($discard);
        $this->em->persist($draw);
        $this->em->flush();

        $message = sprintf('Combat #%s Discard pile #%s has been moved to Draw pile #%s', $combat->getId(), $discard->getId(), $draw->getId());
        $this->logger->info($message);
    }

    /**
     * Combat Event
     *
     * @param End $event
     */
    public function onEnd(End $event)
    {
    }

    /**
     * @param CombatInit $event
     */
    public function onCombatInit(CombatInit $event)
    {
        $combat = $event->getCombat();

        $turn = new Turn();
        $turn->setCombat($combat);
        $turn->setPlayer($combat->getPlayer());
        $turn->setAdversary($combat->getAdversary());
        $turn->setPhase(TurnPhasesEnum::INIT);

        $combat->addTurn($turn);
        $players = [$combat->getPlayer(), $combat->getAdversary()];

        /** @var Target $player */
        foreach ($players as $player) {
            # Moving deck into draw
            $cards     = $player->getDeck()->getCards();
            $positions = [];
            for ($i = 0; $i < $cards->count(); $i++) {
                $positions[] = $i;
            }
            shuffle($positions);

            /** @var ProxyCard $proxy */
            foreach ($cards as $index => $proxy) {
                $player->getDraw()->addCard($proxy, $positions[$index]);
            }
        }

        # Drawing the first hand to both players
        foreach ($players as $player) {
            $handSize = $player->getPlayer()->getCharacteristics()->getHandSize();

            for ($i = 0; $i < $handSize; $i++) {
                $card = $player->getDraw()->draw();

                if ($card === null) {
                    continue;
                }

                $player->getHand()->pushCard($card);
            }
        }

        $this->em->persist($combat);
        $this->em->flush();

        $message = sprintf('Combat #%s initialised', $combat->getId());
        $this->logger->info($message);
    }

    /**
     * Combat Event
     *
     * @param CombatLose $event
     *
     * @throws \Rebelion\Exceptions\CombatException
     * @throws \Rebelion\Exceptions\TurnException
     */
    public function onLose(CombatLose $event)
    {
        $combat = $event->getCombat();
        $turn   = $combat->getCurrentTurn();

        $this->turnService->setTurn($turn);
        $this->turnService->switchPhase(TurnPhasesEnum::END);

        $this->combatService->setCombat($combat);
        $this->combatService->switchPhase(CombatPhasesEnum::END_COMBAT);
    }

    /**
     * @param NextTurn $event
     *
     * @throws \Rebelion\Exceptions\CombatException
     */
    public function onNextTurn(NextTurn $event)
    {
        $combat = $event->getCombat();

        $this->combatService->setCombat($combat);
        $this->combatService->checkCurrentPhase();
    }

    /**
     * @param PlayTurn $event
     *
     * @throws \Rebelion\Exceptions\TurnException
     */
    public function onPlayTurn(PlayTurn $event)
    {
        $combat = $event->getCombat();

        $turn = $combat->getCurrentTurn();
        $this->turnService->setTurn($turn);
        $this->turnService->checkCurrentPhase();
    }

    /**
     * Combat Event
     *
     * @param ProxyCardPlayed $event
     */
    public function onProxyCardPlayed(ProxyCardPlayed $event)
    {
        # Moving proxy to discard pile
        /** @var Combat $combat */
        $combat = $event->getCombat();

        /** @var ProxyCard $proxy */
        $proxy = $event->getProxy();

        /** @var Discard $discard */
        $discard = $combat->getCurrentTurn()->getPlayer()->getDiscard();

        /** @var Hand $hand */
        $hand = $combat->getCurrentTurn()->getPlayer()->getHand();

        # Moving proxy from hand to discard pile
        $hand->removeCard($proxy);
        $discard->pushCard($proxy);

        # TODO : This should not be here, have to move it into turn or combat service
        $player          = $combat->getCurrentTurn()->getPlayer();
        $adversary       = $combat->getCurrentTurn()->getAdversary();
        $payerIsDead     = $player->getPlayer()->getCharacteristics()->getHp() <= 0;
        $adversaryIsDead = $adversary->getPlayer()->getCharacteristics()->getHp() <= 0;

        if ($payerIsDead || $adversaryIsDead) {
            # TODO : Check if the target is not already dead...
            $event = new DeadTarget($combat, ($payerIsDead ? $player : $adversary));
            $this->dispatcher->dispatch(DeadTarget::NAME, $event);
        }

        $this->em->persist($combat);
        $this->em->flush();
    }

    /**
     * Combat Event
     *
     * @param ProxyCardDiscarded $event
     */
    public function onProxyCardDiscarded(ProxyCardDiscarded $event)
    {
        $combat  = $event->getCombat();
        $proxy   = $event->getProxy();
        $turn    = $combat->getCurrentTurn();
        $player  = $turn->getPlayer();
        $hand    = $player->getHand();
        $discard = $player->getDiscard();

        $hand->removeCard($proxy);
        $discard->pushCard($proxy);

        if ($proxy->getParent()->isReplaceOnDiscard()) {
            $card = $player->getDraw()->draw();

            if ($card !== null) {
                $hand->pushCard($card);
            }
        }
    }

    /**
     * Combat Event
     *
     * @param ProxyEffectResolved $event
     */
    public function onProxyEffectResolved(ProxyEffectResolved $event)
    {
        /** @var ProxyEffect $proxy */
        $proxy = $event->getProxy();

        /** @var array $targets */
        $targets = $event->getTargets();

        # Persist effect targets
        foreach ($targets as $target) {
            $this->em->persist($target);
        }
        $this->em->flush();

        $message = sprintf('ProxyEffect #%s resolved', $proxy->getId());
        $this->logger->info($message);
    }

    /**
     * Combat Event
     *
     * @param StartTurn $event
     */
    public function onStartTurn(StartTurn $event)
    {
        $turn   = $event->getCombat()->getCurrentTurn();
        $player = $turn->getPlayer();

        # TODO : Resolve poison effects here

        $message = sprintf('Turn #%s started', $turn->getId());
        $this->logger->info($message);
    }

    /**
     * Combat Event
     *
     * @param CombatWin $event
     *
     * @throws \Rebelion\Exceptions\CombatException
     * @throws \Rebelion\Exceptions\TurnException
     */
    public function onWin(CombatWin $event)
    {
        $combat = $event->getCombat();
        $turn   = $combat->getCurrentTurn();

        $this->turnService->setTurn($turn);
        $this->turnService->switchPhase(TurnPhasesEnum::END);

        $this->combatService->setCombat($combat);
        $this->combatService->switchPhase(CombatPhasesEnum::END_COMBAT);
    }
}