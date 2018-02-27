<?php
/**
 * Created by PhpStorm.
 * User: Morphinof
 * Date: 22/12/2017
 * Time: 22:00
 */

namespace Rebelion\Event\Listener;

use Rebelion\Entity\Combat;
use Rebelion\Entity\Turn;
use Symfony\Component\Workflow\Event\GuardEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * TODO : Update this listener to do things...
 *
 * Class CombatPhaseListener
 * @package Rebelion\Event\Listener
 */
class CombatPhaseListener implements EventSubscriberInterface
{
    public function guardPhase(GuardEvent $event)
    {
        /** @var Combat $combat */
        $combat = $event->getSubject();

        /** @var Turn $turn */
        $turn = $combat->getTurns()->last();
    }

    public static function getSubscribedEvents()
    {
        return array(
            'workflow.combat.guard.end_combat' => array('guardPhase'),
        );
    }
}