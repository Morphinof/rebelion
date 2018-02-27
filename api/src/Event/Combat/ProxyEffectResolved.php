<?php
/**
 * Created by PhpStorm.
 * User: Morphinof
 * Date: 04/02/2018
 * Time: 08:25
 */

namespace Rebelion\Event\Combat;

use Rebelion\Entity\Effect\ProxyEffect;
use Symfony\Component\EventDispatcher\Event;

/**
 * The rebelion.combat.proxy_effect_resolved event is dispatched each time a card is played in the system.
 */
class ProxyEffectResolved extends Event
{
    const NAME = 'rebelion.event.combat.proxy_effect.resolved';

    /** @var ProxyEffect */
    protected $proxy;

    /** @var array $targets */
    protected $targets;

    /**
     * CardPlayedEvent constructor.
     *
     * @param ProxyEffect $proxy
     * @param array       $targets
     */
    public function __construct(ProxyEffect $proxy, array &$targets)
    {
        $this->proxy   = $proxy;
        $this->targets = $targets;
    }

    /**
     * @return ProxyEffect
     */
    public function getProxy(): ProxyEffect
    {
        return $this->proxy;
    }

    /**
     * @return array
     */
    public function getTargets(): array
    {
        return $this->targets;
    }
}