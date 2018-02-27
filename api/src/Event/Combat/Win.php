<?php
/**
 * Created by PhpStorm.
 * User: Morphinof
 * Date: 04/02/2018
 * Time: 08:25
 */

namespace Rebelion\Event\Combat;

use Rebelion\Entity\Combat;
use Rebelion\Entity\Target;
use Symfony\Component\EventDispatcher\Event;

/**
 * The rebelion.combat.win event is dispatched each time a Human player win a combat.
 */
class Win extends Event
{
    const NAME = 'rebelion.event.combat.win';

    /** @var Combat $combat */
    protected $combat;

    /** @var Target $target */
    protected $target;

    /**
     * Win constructor.
     *
     * @param Combat $combat
     * @param Target $target
     */
    public function __construct(Combat $combat, Target $target)
    {
        $this->combat = $combat;
        $this->target = $target;
    }

    /**
     * @return Combat
     */
    public function getCombat(): Combat
    {
        return $this->combat;
    }

    /**
     * @return Target
     */
    public function getTarget(): Target
    {
        return $this->target;
    }
}