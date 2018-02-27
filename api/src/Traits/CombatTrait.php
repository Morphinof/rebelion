<?php
/**
 * Created by PhpStorm.
 * User: Morphinof
 * Date: 12/12/2017
 * Time: 20:30
 */

namespace Rebelion\Traits;

use Rebelion\Entity\Target;
use Rebelion\Enum\CombatPhasesEnum;
use Rebelion\Entity\Turn;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OrderBy;

/**
 * Trait CombatTrait
 *
 * @package Rebelion\Traits
 */
trait CombatTrait
{
    use EntityTrait;

    /**
     * Current combat phase
     *
     * @var string
     *
     * @ORM\Column(name="phase", type="string", length=64)
     */
    protected $phase = CombatPhasesEnum::INIT;

    /**
     * A Combat has many Turns.
     *
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Rebelion\Entity\Turn", mappedBy="combat", cascade={"persist", "remove"})
     * @OrderBy({"updatedAt" = "ASC"})
     */
    protected $turns;

    /**
     * Many Turns have One player Target.
     *
     * @var Target
     *
     * @ORM\ManyToOne(targetEntity="Rebelion\Entity\Target", cascade={"persist"})
     * @ORM\JoinColumn(name="player_1", referencedColumnName="id", nullable=false)
     */
    protected $player = null;

    /**
     * Many Turns have One adversary Target.
     *
     * @var Target
     *
     * @ORM\ManyToOne(targetEntity="Rebelion\Entity\Target", cascade={"persist"})
     * @ORM\JoinColumn(name="player_2", referencedColumnName="id", nullable=false)
     */
    protected $adversary = null;

    /**
     * CombatTrait constructor.
     */
    public function __construct()
    {
        $this->turns = new ArrayCollection();
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
     * @return ArrayCollection
     */
    public function getTurns()
    {
        return $this->turns;
    }

    /**
     * @param ArrayCollection $turns
     */
    public function setTurns(ArrayCollection $turns): void
    {
        $this->turns = $turns;
    }

    /**
     * Add a turn to the combat
     *
     * @param Turn $turn
     */
    public function addTurn(Turn $turn): void
    {
        if (!$this->turns->contains($turn)) {
            $this->turns->add($turn);
        }
    }

    /**
     * @return Turn
     */
    public function getCurrentTurn(): Turn
    {
        return $this->turns->last();
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
     * Returns combat available targets
     *
     * @param bool $ids
     *
     * @return array
     */
    public function getTargets($ids = false)
    {
        /**
         * TODO: Get Ai sub targets
         */
        $others = [];

        return [
                $ids ? $this->player->getId() : $this->player,
                $ids ? $this->adversary->getId() : $this->adversary,
            ] + $others;
    }

    /**
     * @return null|Target
     */
    public function getWinner(): ?Target
    {
        if ($this->player->getPlayer()->getCharacteristics()->getHp() > 0 && $this->adversary->getPlayer()->getCharacteristics()->getHp() <= 0) {
            return $this->player;
        } else {
            if ($this->adversary->getPlayer()->getCharacteristics()->getHp() > 0 && $this->player->getPlayer()->getCharacteristics()->getHp() <= 0) {
                return $this->adversary;
            }
        }

        return null;
    }
}