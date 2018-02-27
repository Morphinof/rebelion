<?php

namespace Rebelion\Entity\Effect;

use Doctrine\ORM\Mapping as ORM;
use Rebelion\Traits\ProxyEffectTrait;

/**
 * @ORM\Entity(repositoryClass="Rebelion\Repository\ProxyEffectRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class ProxyEffect
{
    use ProxyEffectTrait;

    /**
     * ProxyEffectTrait constructor.
     *
     * @param Effect $parent
     */
    public function __construct(Effect $parent = null)
    {
        $this->parent = $parent;

        if ($parent !== null) {
            $this->setParameters($parent->getParameters());
        }
    }
}

