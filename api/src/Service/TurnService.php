<?php
/**
 * Created by PhpStorm.
 * User: Morphinof
 * Date: 10/12/2017
 * Time: 13:02
 */

namespace Rebelion\Service;

use Rebelion\Abstracts\ServiceAbstract;
use Rebelion\Entity\Turn;
use Rebelion\Enum\ActionTypeEnum;
use Rebelion\Enum\CombatPhasesEnum;
use Rebelion\Enum\TurnPhasesEnum;
use Rebelion\Event\Combat\Turn\Draw;
use Rebelion\Event\Combat\Turn\End;
use Rebelion\Event\Combat\Turn\Init;
use Rebelion\Event\Combat\Turn\Main;
use Rebelion\Exceptions\TurnException;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\SubmitButton;
use Symfony\Component\Workflow\Registry;
use Symfony\Component\Workflow\Workflow;

class TurnService extends ServiceAbstract
{
    /** @var EntityManagerInterface $entityManager */
    private $em;

    /** @var LoggerInterface $logger */
    private $logger;

    /** @var Registry $registry */
    private $registry;

    /** @var Workflow $workflow */
    private $workflow;

    /** @var Turn $turn */
    private $turn;

    /** @var EventDispatcherInterface $dispatcher */
    private $dispatcher;

    /**
     * TurnService constructor.
     *
     * @param EntityManagerInterface   $entityManager
     * @param LoggerInterface          $logger
     * @param Registry                 $registry
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        LoggerInterface $logger,
        Registry $registry,
        EventDispatcherInterface $dispatcher
    ) {
        $this->em         = $entityManager;
        $this->logger     = $logger;
        $this->registry   = $registry;
        $this->workflow   = null;
        $this->dispatcher = $dispatcher;
    }

    /**
     * Set the turn to manage, sets also the workflow state machine
     *
     * @param Turn $turn
     *
     * @throws TurnException
     */
    public function setTurn(Turn $turn)
    {
        if ($turn->getPlayer() === null) {
            $message = sprintf('Empty player on turn #%s', $turn->getId());
            throw new TurnException($turn, $message);
        }

        if ($turn->getAdversary() === null) {
            $message = sprintf('Empty adversary on combat #%s', $turn->getId());
            throw new TurnException($turn, $message);
        }

        if ($turn->getPlayer()->getDeck()->getCards()->isEmpty()) {
            $message = sprintf('Empty deck for player #%s', $turn->getPlayer()->getPlayer()->getId());
            throw new TurnException($turn, $message);
        }

        $this->turn     = $turn;
        $this->workflow = $this->registry->get($this->turn);

        if ($this->workflow === null) {
            $message = sprintf('Unable to get workflow for turn #%s', $this->turn->getId());
            throw new TurnException($this->turn, $message);
        }
    }

    /**
     * @throws TurnException
     */
    public function checkCurrentPhase(): void
    {
        $turn = $this->turn;

        try {
            switch ($turn->getPhase()) {
                case TurnPhasesEnum::INIT:
                    $event = new Init($turn);
                    $this->dispatcher->dispatch(Init::NAME, $event);
                    $this->switchPhase(TurnPhasesEnum::DRAW);
                    break;
                case TurnPhasesEnum::DRAW:
                    $event = new Draw($turn);
                    $this->dispatcher->dispatch(Draw::NAME, $event);
                    $this->switchPhase(TurnPhasesEnum::MAIN);
                    break;
                case TurnPhasesEnum::MAIN:
                    $event = new Main($turn);
                    $this->dispatcher->dispatch(Main::NAME, $event);
                    break;
                case TurnPhasesEnum::END:
                    $event = new End($turn);
                    $this->dispatcher->dispatch(End::NAME, $event);
                    break;
            }
        } catch (\Exception $e) {
            $message = sprintf('%s - %s()', self::class, __FUNCTION__);

            throw new TurnException($turn, $message, $e);
        }
    }

    /**
     * @param string $phase
     *
     * @throws TurnException
     */
    public function switchPhase(string $phase): void
    {
        $turn = $this->turn;

        if (!$this->workflow->can($turn, $phase)) {
            $message = sprintf('Turn #%s cannot switch from phase %s to phase %s', $turn->getId(), $turn->getPhase(), $phase);

            throw new TurnException($turn, $message);
        }

        $message = sprintf('Turn #% switching from phase %s to phase "%s"', $turn->getId(), $turn->getPhase(), $phase);
        $this->logger->info($message);

        $this->workflow->apply($turn, $phase);
        $turn->setPhase($phase);

        $this->em->persist($turn);
        $this->em->flush();

        $this->checkCurrentPhase();
    }

    /**
     * @param Turn          $turn
     * @param FormInterface $actionForm
     *
     * @return string
     * @throws TurnException
     */
    public function action(Turn $turn, FormInterface $actionForm): string
    {
        $message = null;
        $this->setTurn($turn);

        /** @var SubmitButton $endTurnButton */
        $endTurnButton = $actionForm->get(ActionTypeEnum::END_TURN);

        /** @var SubmitButton $endCombatButton */
        $endCombatButton = $actionForm->get(ActionTypeEnum::END_COMBAT);

        if ($endTurnButton->isClicked()) {
            $this->switchPhase(TurnPhasesEnum::END);
            $message = 'end_turn_message';
        } elseif ($endCombatButton->isClicked()) {
            $this->switchPhase(CombatPhasesEnum::END_COMBAT);
            $message = 'end_combat_message';
        }

        return $message;
    }
}