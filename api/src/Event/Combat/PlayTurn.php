<?php
/**
 * Created by PhpStorm.
 * User: Morphinof
 * Date: 04/02/2018
 * Time: 08:25
 */

namespace Rebelion\Event\Combat;

use Rebelion\Entity\Combat;
use Rebelion\Service\TurnService;
use Symfony\Component\EventDispatcher\Event;

/**
 * The rebelion.event.combat.start event is dispatched after combat init.
 */
class PlayTurn extends Event
{
    const NAME = 'rebelion.event.combat.play_turn';

    /** @var Combat */
    protected $combat;

    /** @var TurnService */
    protected $turnService;

    /**
     * CardPlayedEvent constructor.
     *
     * @param Combat      $combat
     * @param TurnService $turnService
     */
    public function __construct(Combat $combat, TurnService $turnService)
    {
        $this->turnService = $turnService;
        $this->combat      = $combat;
    }

    /**
     * @return Combat
     */
    public function getCombat(): Combat
    {
        return $this->combat;
    }

    /**
     * @return TurnService
     */
    public function getTurnService(): TurnService
    {
        return $this->turnService;
    }
}