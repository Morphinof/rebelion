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
 * The rebelion.event.combat.turn.draw event is dispatched after draw phase.
 */
class Main extends Event
{
    const NAME = 'rebelion.event.combat.turn.main';

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