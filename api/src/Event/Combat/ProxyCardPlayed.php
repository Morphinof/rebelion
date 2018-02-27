<?php
/**
 * Created by PhpStorm.
 * User: Morphinof
 * Date: 04/02/2018
 * Time: 08:25
 */

namespace Rebelion\Event\Combat;

use Rebelion\Entity\Combat;
use Rebelion\Entity\Container\ProxyCard;
use Symfony\Component\EventDispatcher\Event;

/**
 * The rebelion.combat.proxy_card_played event is dispatched each time a card is played in the system.
 */
class ProxyCardPlayed extends Event
{
    const NAME = 'rebelion.event.combat.proxy_card.played';

    /** @var Combat */
    protected $combat;

    /** @var ProxyCard */
    protected $proxy;

    /**
     * CardPlayedEvent constructor.
     *
     * @param Combat    $combat
     * @param ProxyCard $proxy
     */
    public function __construct(Combat $combat, ProxyCard $proxy)
    {
        $this->combat = $combat;
        $this->proxy  = $proxy;
    }

    /**
     * @return Combat
     */
    public function getCombat(): Combat
    {
        return $this->combat;
    }

    /**
     * @return ProxyCard
     */
    public function getProxy(): ProxyCard
    {
        return $this->proxy;
    }
}