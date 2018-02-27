<?php
/**
 * Created by PhpStorm.
 * User: Morphinof
 * Date: 04/02/2018
 * Time: 08:25
 */

namespace Rebelion\Event\Combat;

use Rebelion\Entity\Combat;
use Rebelion\Entity\Pile\Draw;
use Symfony\Component\EventDispatcher\Event;

/**
 * The rebelion.combat.draw_pile_is_empty event is dispatched each time a draw pile is empty.
 */
class DrawPileIsEmpty extends Event
{
    const NAME = 'rebelion.event.combat.draw_pile_is_empty';

    /** @var Combat */
    protected $combat;

    /** @var Draw */
    protected $draw;

    /**
     * CardPlayedEvent constructor.
     *
     * @param Combat $combat
     * @param Draw   $draw
     */
    public function __construct(Combat $combat, Draw $draw)
    {
        $this->combat = $combat;
        $this->draw   = $draw;
    }

    /**
     * @return Combat
     */
    public function getCombat(): Combat
    {
        return $this->combat;
    }

    /**
     * @return Draw
     */
    public function getDraw(): Draw
    {
        return $this->draw;
    }
}