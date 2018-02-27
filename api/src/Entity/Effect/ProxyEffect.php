<?php

namespace Rebelion\Entity\Effect;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Rebelion\Traits\ProxyEffectTrait;

/**
 * @ApiResource
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

