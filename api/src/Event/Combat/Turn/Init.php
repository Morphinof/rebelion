<?php
/**
 * Created by PhpStorm.
 * User: Morphinof
 * Date: 04/02/2018
 * Time: 08:25
 */

namespace Rebelion\Event\Combat\Turn;

use Rebelion\Entity\Turn;
use Symfony\Component\EventDispatcher\Event;

/**
 * The rebelion.event.combat.turn.init event is dispatched before turn starts.
 */
class Init extends Event
{
    const NAME = 'rebelion.event.combat.turn.init';

    /** @var Turn */
    protected $turn;

    /**
     * CardPlayedEvent constructor.
     *
     * @param Turn $turn
     */
    public function __construct(Turn $turn)
    {
        $this->turn = $turn;
    }

    /**
     * @return Turn
     */
    public function getTurn(): Turn
    {
        return $this->turn;
    }
}