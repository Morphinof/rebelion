<?php

namespace Rebelion\Abstracts;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Rebelion\Entity\Effect\Effect;
use Rebelion\Entity\Effect\ProxyEffect;

/**
 * Class ProxyEffectContainerAbstract
 *
 * @package Rebelion\Abstracts
 */
abstract class ProxyEffectContainerAbstract
{
    const CLASS_NAME = 'ProxyEffectContainerAbstract';

    /**
     * @var ArrayCollection
     *
     * Many ProxyEffectContainers have Many ProxyEffects.
     *
     * @ORM\ManyToMany(targetEntity="Rebelion\Entity\Effect\ProxyEffect", cascade={"persist", "remove"})
     * @ORM\JoinTable
     * (
     *      joinColumns={@ORM\JoinColumn(name="proxy_effect_container_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="proxy_effect_id", referencedColumnName="id")}
     * )
     */
    protected $effects;

    /**
     * Pile constructor.
     */
    public function __construct()
    {
        $this->effects = new ArrayCollection();
    }

    /**
     * @return ArrayCollection
     */
    public function getEffects()
    {
        return $this->effects;
    }

    /**
     * @param ArrayCollection $effects
     */
    public function setEffects(ArrayCollection $effects): void
    {
        $this->effects = $effects;
    }

    /**
     * Add proxy effect to container
     *
     * @param Effect|ProxyEffect $proxy
     */
    public function addEffect($proxy): void
    {
        if ($proxy instanceof Effect) {
            $proxy = new ProxyEffect($proxy);
        }

        if (!$this->effects->contains($proxy)) {
            $this->effects->add($proxy);
        }
    }

    /**
     * @param ProxyEffect $proxy
     */
    public function removeEffect(ProxyEffect $proxy): void
    {
        /** @var ProxyEffect $currentProxy */
        foreach ($this->effects as $currentProxy) {
            if ($currentProxy->getId() === $proxy->getId()) {
                $this->effects->removeElement($proxy);
            }
        }
    }

    /**
     * @return array
     */
    public function __toJson(): array
    {
        return [
            'effects' => $this->effects->toArray()
        ];
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $format  = 'EffectContainer #%s has %s effects [%s]';
        $effects = [];
        if (!$this->effects->isEmpty()) {
            $effects = $this->effects->toArray();
        }

        return sprintf(
            $format,
            get_class($this),
            count($effects),
            implode(', ', $effects)
        );
    }

    /**
     * @return string
     */
    public function getClassName(): string
    {
        return static::CLASS_NAME;
    }
}
