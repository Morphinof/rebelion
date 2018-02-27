<?php
/**
 * Created by PhpStorm.
 * User: Morphinof
 * Date: 13/12/2017
 * Time: 23:58
 */

namespace Rebelion\Traits;

use Doctrine\Common\Collections\ArrayCollection;
use Rebelion\Entity\Container\Card;
use Doctrine\ORM\Mapping as ORM;
use Rebelion\Enum\CardStatusEnum;
use Rebelion\Entity\Effect\ProxyEffect;

/**
 * ProxyCardTrait ActionTrait
 *
 * @package Rebelion\Traits
 */
trait ProxyCardTrait
{
    use EntityTrait;

    /**
     * @var Card
     *
     * One ProxyCard has One parent Card.
     *
     * @ORM\ManyToOne(targetEntity="Rebelion\Entity\Container\Card")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     */
    protected $parent;

    /**
     * @var integer
     *
     * @ORM\Column(name="level", type="integer")
     */
    protected $level = 1;

    /**
     * @var integer
     *
     * @ORM\Column(name="exp", type="integer")
     */
    protected $exp = 0;

    /**
     * @var string
     * @Assert\NotBlank()
     *
     * @ORM\Column(name="status", type="string", length=64, nullable=false)
     */
    protected $status = CardStatusEnum::PLAYABLE;

    /**
     * ProxyCardTrait constructor.
     *
     * @param Card $card
     */
    public function __construct(Card $card)
    {
        $this->parent = $card;
    }

    /**
     * @return Card
     */
    public function getParent(): Card
    {
        return $this->parent;
    }

    /**
     * @param Card $parent
     */
    public function setParent(Card $parent): void
    {
        $this->parent = $parent;
    }

    /**
     * @return int
     */
    public function getParentId()
    {
        return $this->parent->getId();
    }

    /**
     * @return int
     */
    public function getLevel(): int
    {
        return $this->level;
    }

    /**
     * @param int $level
     */
    public function setLevel(int $level): void
    {
        $this->level = $level;
    }

    /**
     * @return int
     */
    public function getExp(): int
    {
        return $this->exp;
    }

    /**
     * @param int $exp
     */
    public function setExp(int $exp): void
    {
        $this->exp = $exp;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    /**
     * @return null|string
     */
    public function getName()
    {
        return $this->parent->getName();
    }

    /**
     * @return null|string
     */
    public function getDescription()
    {
        return $this->parent->getDescription();
    }

    /**
     * @return ArrayCollection
     */
    public function getEffects()
    {
        return $this->parent->getEffects();
    }

    /**
     * @return array
     */
    public function getTargetSequence(): array
    {
        $sequence = [];

        /** @var ProxyEffect $effect */
        foreach ($this->getEffects() as $effect) {
            $sequence[] = [
                'mode' => $effect->getParent()->getTargetMode(),
                'type' => $effect->getParent()->getTargetType()
            ];
        }

        return $sequence;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return sprintf('#%s %s (%s)', $this->id, $this->parent->getName(), $this->getStatus());
    }
}