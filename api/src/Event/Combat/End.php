<?php
/**
 * Created by PhpStorm.
 * User: Morphinof
 * Date: 04/02/2018
 * Time: 08:25
 */

namespace Rebelion\Event\Combat;

use Rebelion\Entity\Combat;
use Symfony\Component\EventDispatcher\Event;

/**
 * The rebelion.event.combat.end event is dispatched on combat end.
 */
class End extends Event
{
    const NAME = 'rebelion.event.combat.end';

    /** @var Combat */
    protected $combat;

    /**
     * CardPlayedEvent constructor.
     *
     * @param Combat $combat
     */
    public function __construct(Combat $combat)
    {
        $this->combat = $combat;
    }

    /**
     * @return Combat
     */
    public function getCombat(): Combat
    {
        return $this->combat;
    }
}