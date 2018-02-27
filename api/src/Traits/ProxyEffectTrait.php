<?php
/**
 * Created by PhpStorm.
 * User: Morphinof
 * Date: 21/01/2018
 * Time: 14:56
 */

namespace Rebelion\Traits;

use Rebelion\Entity\Effect\Effect;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Trait ProxyEffectTrait
 *
 * @package Rebelion\Traits
 */
trait ProxyEffectTrait
{
    use EntityTrait;

    /**
     * @var Effect
     *
     * One ProxyCard has One parent Card.
     *
     * @ORM\ManyToOne(targetEntity="Rebelion\Entity\Effect\Effect")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     * @Assert\NotBlank()
     */
    protected $parent;

    /**
     * @var array
     *
     * @ORM\Column(name="parameters", type="json_array", nullable=true)
     */
    protected $parameters;

    /**
     * @return null|string
     */
    public function getName(): ?string
    {
        return $this->parent->getName();
    }

    /**
     * @return null|string
     */
    public function getDescription(): ?string
    {
        return $this->parent->getDescription();
    }

    /**
     * @return Effect
     */
    public function getParent(): ?Effect
    {
        return $this->parent;
    }

    /**
     * @param Effect $parent
     */
    public function setParent(Effect $parent): void
    {
        $this->parent = $parent;
    }

    /**
     * @return array|null
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @param array $parameters
     */
    public function setParameters(array $parameters): void
    {
        $this->parameters = $parameters;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return sprintf('[%s] %s *', $this->id, $this->parent->getName());
    }
}