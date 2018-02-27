<?php
/**
 * Created by PhpStorm.
 * User: Morphinof
 * Date: 10/12/2017
 * Time: 13:25
 */

namespace Rebelion\Traits;

use Rebelion\Entity\Action\Action;
use Rebelion\Entity\Action\DiscardCard;
use Rebelion\Entity\Action\PlayCard;
use Rebelion\Entity\Combat;
use Rebelion\Entity\Target;
use Rebelion\Enum\TurnPhasesEnum;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Trait TurnTrait
 *
 * @package Rebelion\Traits
 */
trait TurnTrait
{
    use EntityTrait;

    /**
     * Current combat phase
     *
     * @var string
     *
     * @ORM\Column(name="phase", type="string", length=64)
     */
    protected $phase = TurnPhasesEnum::INIT;

    /**
     * Many Turns have One Combat.
     *
     * @var Combat
     *
     * @ORM\ManyToOne(targetEntity="Rebelion\Entity\Combat", inversedBy="turns")
     * @ORM\JoinColumn(name="combat_id", referencedColumnName="id")
     */
    protected $combat;

    /**
     * Many Turns have One player Target.
     *
     * @var Target
     *
     * @ORM\ManyToOne(targetEntity="Rebelion\Entity\Target", cascade={"persist"})
     * @ORM\JoinColumn(name="player_id", referencedColumnName="id")
     */
    protected $player;

    /**
     * Many Turns have One adversary Target.
     *
     * @var Target
     *
     * @ORM\ManyToOne(targetEntity="Rebelion\Entity\Target", cascade={"persist"})
     * @ORM\JoinColumn(name="adversary_id", referencedColumnName="id")
     */
    protected $adversary;

    /**
     * A Turn has many Actions.
     *
     * @ORM\OneToMany(targetEntity="Rebelion\Entity\Action\Action", mappedBy="turn", cascade={"persist", "remove"})
     */
    protected $actions;

    /**
     * TurnTrait constructor.
     */
    public function __construct()
    {
        $this->actions = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getPhase(): string
    {
        return $this->phase;
    }

    /**
     * @param string $phase
     */
    public function setPhase(string $phase): void
    {
        $this->phase = $phase;
    }

    /**
     * @return Combat
     */
    public function getCombat(): Combat
    {
        return $this->combat;
    }

    /**
     * @param mixed $combat
     */
    public function setCombat($combat): void
    {
        $this->combat = $combat;
    }

    /**
     * @return ArrayCollection
     */
    public function getActions()
    {
        return $this->actions;
    }

    /**
     * @param ArrayCollection $actions
     */
    public function setActions(ArrayCollection $actions): void
    {
        $this->actions = $actions;
    }

    /**
     * Add an Action to actions
     *
     * @param Action $action
     */
    public function addAction(Action $action): void
    {
        if (!$this->actions->contains($action)) {
            $this->actions->add($action);
        }
    }

    /**
     * @return Target
     */
    public function getPlayer(): Target
    {
        return $this->player;
    }

    /**
     * @param Target $player
     */
    public function setPlayer(Target $player): void
    {
        $this->player = $player;
    }

    /**
     * @return Target
     */
    public function getAdversary(): Target
    {
        return $this->adversary;
    }

    /**
     * @param Target $adversary
     */
    public function setAdversary(Target $adversary): void
    {
        $this->adversary = $adversary;
    }

    /**
     * @return int
     */
    public function getCost(): int
    {
        $cost = 0;
        if (!$this->actions->isEmpty()) {
            /** @var DiscardCard|PlayCard $action */
            foreach ($this->actions as $action) {
                switch (true) {
                    case $action instanceof PlayCard:
                        $cost += $action->getCard()->getParent()->getCost();
                        break;
                    case $action instanceof DiscardCard:
                        break;
                }

            }
        }

        return $cost;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        if ($this->actions->isEmpty()) {
            return 'empty_turn';
        }

        $string = [];
        /** @var Action $action */
        foreach ($this->actions as $action) {
            $string[] = get_class($action);
        }

        return implode(' , ', $string);
    }
}