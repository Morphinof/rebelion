<?php
/**
 * Created by PhpStorm.
 * User: Morphinof
 * Date: 10/12/2017
 * Time: 13:02
 */

namespace Rebelion\Service;

use Rebelion\Abstracts\ServiceAbstract;
use Rebelion\Entity\Action\DiscardCard;
use Rebelion\Entity\Action\PlayCard;
use Rebelion\Entity\Combat;
use Rebelion\Entity\Container\ProxyCard;
use Rebelion\Entity\Effect\ProxyEffect;
use Rebelion\Entity\Target;
use Rebelion\Entity\Turn;
use Rebelion\Enum\CardStatusEnum;
use Rebelion\Enum\CombatPhasesEnum;
use Rebelion\Enum\TargetModeEnum;
use Rebelion\Event\Combat\End;
use Rebelion\Event\Combat\Init;
use Rebelion\Event\Combat\PlayTurn;
use Rebelion\Event\Combat\ProxyCardDiscarded;
use Rebelion\Event\Combat\ProxyCardPlayed;
use Rebelion\Event\Combat\StartTurn;
use Rebelion\Exceptions\CombatException;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Workflow\Registry;
use Symfony\Component\Workflow\Workflow;

class CombatService extends ServiceAbstract
{
    /** @var EntityManagerInterface $entityManager */
    private $em;

    /** @var LoggerInterface $logger */
    private $logger;

    /** @var Registry $registry */
    private $registry;

    /** @var Workflow $combatWorkflow */
    private $combatWorkflow;

    /** @var Workflow $combatWorkflow */
    private $turnWorkflow;

    /** @var TurnService $turnService */
    private $turnService;

    /** @var EffectService $effectService */
    private $effectService;

    /** @var EventDispatcherInterface $dispatcher */
    private $dispatcher;

    /** @var Combat $combat */
    private $combat;

    /**
     * CombatService constructor.
     *
     * @param EntityManagerInterface   $entityManager
     * @param LoggerInterface          $logger
     * @param Registry                 $registry
     * @param TurnService              $turnService
     * @param EffectService            $effectService
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        LoggerInterface $logger,
        Registry $registry,
        TurnService $turnService,
        EffectService $effectService,
        EventDispatcherInterface $dispatcher
    ) {
        $this->em             = $entityManager;
        $this->logger         = $logger;
        $this->registry       = $registry;
        $this->combatWorkflow = null;
        $this->turnWorkflow   = null;
        $this->turnService    = $turnService;
        $this->effectService  = $effectService;
        $this->dispatcher     = $dispatcher;
    }

    /**
     * Set the combat to manage, sets also the workflow state machine
     *
     * @param Combat $combat
     *
     * @throws CombatException
     */
    public function setCombat(Combat $combat)
    {
        if ($combat->getPlayer() === null) {
            $message = sprintf('Empty player on combat #%s', $combat->getId());
            throw new CombatException($message, $combat);
        }

        if ($combat->getAdversary() === null) {
            $message = sprintf('Empty adversary on combat #%s', $combat->getId());
            throw new CombatException($message, $combat);
        }

        $this->combat         = $combat;
        $this->combatWorkflow = $this->registry->get($this->combat);

        if ($this->combatWorkflow === null) {
            $message = sprintf('Unable to get workflow for combat #%s', $this->combat->getId());

            throw new CombatException($message, $this->combat);
        }
    }

    /**
     * @return Combat
     *
     * @throws CombatException
     */
    public function getCombat(): Combat
    {
        if ($this->combat === null) {
            $message = sprintf('Empty combat');
            throw new CombatException($message);
        }

        return $this->combat;
    }

    /**
     * @throws CombatException
     */
    public function checkCurrentPhase(): void
    {
        $combat = $this->combat;

        try {
            switch ($combat->getPhase()) {
                case CombatPhasesEnum::INIT:
                    $event = new Init($combat);
                    $this->dispatcher->dispatch(Init::NAME, $event);
                    $this->switchPhase(CombatPhasesEnum::START_TURN);
                    break;
                case CombatPhasesEnum::START_TURN:
                    $event = new StartTurn($combat);
                    $this->dispatcher->dispatch(StartTurn::NAME, $event);
                    $this->switchPhase(CombatPhasesEnum::PLAY_TURN);
                    break;
                case CombatPhasesEnum::PLAY_TURN:
                    $event = new PlayTurn($combat, $this->turnService);
                    $this->dispatcher->dispatch(PlayTurn::NAME, $event);
                    break;
                case CombatPhasesEnum::END_COMBAT:
                    $event = new End($combat);
                    $this->dispatcher->dispatch(End::NAME, $event);
                    break;
            }
        } catch (\Exception $e) {
            $message = sprintf('%s - %s()', self::class, __FUNCTION__);

            throw new CombatException($message, $combat, $e);
        }
    }

    /**
     * Play a card
     *
     * @param ProxyCard $proxy
     * @param Request   $request
     *
     * @return string
     * @throws CombatException
     */
    public function playCard(ProxyCard $proxy, Request $request): string
    {
        $combat = $this->combat;

        if ($combat === null) {
            throw new CombatException('Missing combat');
        }

        $turn    = $combat->getCurrentTurn();
        $targets = $request->request->get('targets');

        $this->checkCard($combat, $proxy);
        $this->checkAP($combat, $proxy);
        $this->isPlayable($combat, $proxy);
        $action = $this->createPlayCardAction($turn, $proxy);
        $this->setActionTargets($action, $combat, $proxy, $targets);

        if (empty($action->getTargets())) {
            throw new CombatException(sprintf('Action has no targets'), $combat);
        }

        try {
            if ($this->effectService->resolvePlayCardAction($action)) {
                $turn->addAction($action);

                $this->em->persist($combat);
                $this->em->flush();
            } else {
                $message = sprintf(
                    'Combat #%s, on Turn #%s Card #%s resolve has failed',
                    $combat->getId(),
                    $turn->getId(),
                    $proxy->getParentId()
                );

                $this->logger->info($message);
            }
        } catch (\Exception $exception) {
            $format = 'Combat #%s, on Turn #%s Card #%s resolve has failed with exception %s in file %s line %s <br /> <pre>%s</pre>';

            $message = sprintf(
                $format,
                $combat->getId(),
                $turn->getId(),
                $proxy->getParentId(),
                $exception->getMessage(),
                $exception->getFile(),
                $exception->getLine(),
                $exception->getTraceAsString()
            );

            $this->logger->info($message);
        }

        $event = new ProxyCardPlayed($combat, $proxy);
        $this->dispatcher->dispatch(ProxyCardPlayed::NAME, $event);

        $message = sprintf(
            'Combat #%s, on Turn #%s Card %s resolved',
            $combat->getId(),
            $turn->getId(),
            $proxy->getParent()->getName()
        );

        return $message;
    }

    /**
     * @param ProxyCard $proxy
     *
     * @return string
     * @throws CombatException
     */
    public function discardCard(ProxyCard $proxy)
    {
        $combat = $this->combat;
        $turn   = $combat->getCurrentTurn();

        if ($combat === null) {
            throw new CombatException('Missing combat');
        }

        $this->checkCard($combat, $proxy);

        try {
            $event = new ProxyCardDiscarded($combat, $proxy);
            $this->dispatcher->dispatch(ProxyCardDiscarded::NAME, $event);

            $action = $this->createDiscardCardAction($turn, $proxy);
            $turn->addAction($action);

            $this->em->persist($combat);
            $this->em->flush();
        } catch (\Exception $exception) {
            $format = 'Combat #%s, on Turn #%s Card #%s resolve has failed with exception %s in file %s line %s <br /> <pre>%s</pre>';

            $message = sprintf(
                $format,
                $combat->getId(),
                $turn->getId(),
                $proxy->getParentId(),
                $exception->getMessage(),
                $exception->getFile(),
                $exception->getLine(),
                $exception->getTraceAsString()
            );

            $this->logger->info($message);
        }

        $message = sprintf(
            'Combat #%s, on Turn #%s Card %s discarded',
            $combat->getId(),
            $turn->getId(),
            $proxy->getParent()->getName()
        );

        return $message;
    }

    /**
     * @param string $phase
     *
     * @throws CombatException
     */
    public function switchPhase(string $phase): void
    {
        if ($this->combat === null) {
            throw new CombatException('Missing combat');
        }

        $combat = $this->combat;

        if (!$this->combatWorkflow->can($combat, $phase)) {
            $message = sprintf('Combat #%s cannot switch to phase %s', $combat->getId(), $phase);

            throw new CombatException($message, $combat);
        }

        $this->combatWorkflow->apply($combat, $phase);

        $message = sprintf('Combat #%s is allowed to switch to phase "%s"', $combat->getId(), $phase);
        $this->logger->info($message);

        $combat->setPhase($phase);
        $this->em->persist($combat);
        $this->em->flush();

        $this->checkCurrentPhase();
    }

    /**
     * @param Turn      $turn
     * @param ProxyCard $proxy
     *
     * @return PlayCard
     */
    private function createPlayCardAction(Turn $turn, ProxyCard $proxy): PlayCard
    {
        $action = new PlayCard();
        $action->setCard($proxy);
        $action->setTurn($turn);

        return $action;
    }

    /**
     * @param Turn      $turn
     * @param ProxyCard $proxy
     *
     * @return DiscardCard
     */
    private function createDiscardCardAction(Turn $turn, ProxyCard $proxy): DiscardCard
    {
        $action = new DiscardCard();
        $action->setCard($proxy);
        $action->setTurn($turn);

        return $action;
    }

    /**
     * @param Combat    $combat
     * @param PlayCard  $action
     * @param array     $targets
     * @param ProxyCard $proxy
     *
     * @throws CombatException
     */
    private function setActionTargets(PlayCard $action, Combat $combat, ProxyCard $proxy, array $targets)
    {
        /** @var ProxyEffect $effect */
        foreach ($proxy->getEffects() as $index => $effect) {
            $expectedNumberOfTargets = $effect->getParent()->getTargetMode(true);
            $receivedEffects         = count($targets[$index]);

            if ($expectedNumberOfTargets !== $receivedEffects) {
                throw new CombatException(
                    sprintf(
                        'Missmatch target number of effect #%d (%s) expected %d got %d',
                        $index,
                        $effect->getName(),
                        $expectedNumberOfTargets,
                        $receivedEffects
                    ), $combat);
            }

            if (!empty($targets[$index])) {
                $proxyEffectTargets = $targets[$index];

                foreach ($proxyEffectTargets as $targetId) {
                    $target = $this->getTarget($effect, $targetId);

                    $action->addTarget($target);
                }
            }
        }
    }

    /**
     * Checks target validity
     *
     * @param ProxyEffect $effect
     * @param int         $targetId
     *
     * @return Target
     * @throws CombatException
     */
    private function getTarget(ProxyEffect $effect, int $targetId): Target
    {
        if ($this->combat === null) {
            throw new CombatException('Missing combat');
        }

        $validTargets = $this->combat->getTargets(true);

        if ($effect->getParent()->getTargetMode() === TargetModeEnum::SELF && $targetId !== $this->combat->getCurrentTurn()->getPlayer()->getId()) {
            throw new CombatException(sprintf(
                'Invalid target #%s expected #%s',
                $targetId,
                $this->combat->getCurrentTurn()->getPlayer()->getId()
            ), $this->combat);
        }

        if (!in_array($targetId, $validTargets)) {
            throw new CombatException(sprintf('Invalid target #%s', $targetId), $this->combat);
        }

        /** @var Target $target */
        $target = $this->em->getRepository('Rebelion:Target')->find($targetId);

        if ($target === null) {
            throw new CombatException(sprintf('Unknown target #%s', $targetId), $this->combat);
        }

        return $target;
    }

    /**
     * @param Combat    $combat
     * @param ProxyCard $proxy
     *
     * @throws CombatException
     */
    private function checkCard(Combat $combat, ProxyCard $proxy): void
    {
        if (!$combat->getCurrentTurn()->getPlayer()->getHand()->getCards()->contains($proxy)) {
            $message = sprintf(
                'Invalid card %s (not in player\'s hand, %s)',
                $proxy,
                $combat->getCurrentTurn()->getPlayer()->getHand()
            );
            $pile    = $combat->getCurrentTurn()->getPlayer()->getCardPile($proxy);

            if ($pile !== null) {
                $message = sprintf('%s, card found in pile %s', $message, $pile);
            }

            throw new CombatException($message, $combat);
        }
    }

    /**
     * @param Combat    $combat
     * @param ProxyCard $proxy
     *
     * @throws CombatException
     */
    private function checkAP(Combat $combat, ProxyCard $proxy): void
    {
        $turnCost = $combat->getCurrentTurn()->getCost();
        $playerAP = $combat->getCurrentTurn()->getPlayer()->getPlayer()->getCharacteristics()->getAp();

        if ($playerAP < ($turnCost + $proxy->getParent()->getCost())) {
            $message = sprintf(
                'Not enought AP to play card "%s" (Cost %d got %d)',
                $proxy->getParent()->getName(),
                $proxy->getParent()->getCost(),
                $playerAP - $turnCost
            );

            throw new CombatException($message, $combat);
        }
    }

    /**
     * @param Combat    $combat
     * @param ProxyCard $proxy
     *
     * @throws CombatException
     */
    private function isPlayable(Combat $combat, ProxyCard $proxy): void
    {
        if ($proxy->getStatus() !== CardStatusEnum::PLAYABLE) {
            $message = sprintf(
                'Card "%s" not playable, current status : %s',
                $proxy->getParent()->getName(),
                $proxy->getStatus()
            );

            throw new CombatException($message, $combat);
        }
    }
}
