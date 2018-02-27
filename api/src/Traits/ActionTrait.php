<?php
/**
 * Created by PhpStorm.
 * User: Morphinof
 * Date: 13/12/2017
 * Time: 23:58
 */

namespace Rebelion\Traits;

use Doctrine\Common\Collections\ArrayCollection;
use Rebelion\Entity\Target;
use Doctrine\ORM\Mapping as ORM;
use Rebelion\Entity\Turn;

/**
 * Trait ActionTrait
 *
 * @package Rebelion\Traits
 */
trait ActionTrait
{
    use EntityTrait;

    /**
     * @var Turn
     *
     * Many Actions have One Turn
     *
     * @ORM\ManyToOne(targetEntity="Rebelion\Entity\Turn", inversedBy="actions")
     * @ORM\JoinColumn(name="turn_id", referencedColumnName="id")
     */
    protected $turn;

    /**
     * @var array
     *
     * @ORM\Column(type="array")
     */
    protected $targets;

    /**
     * ActionTrait constructor.
     */
    public function __construct()
    {
        $this->targets = [];
    }


    /**
     * @return Turn
     */
    public function getTurn(): Turn
    {
        return $this->turn;
    }

    /**
     * @param Turn $turn
     */
    public function setTurn(Turn $turn): void
    {
        $this->turn = $turn;
    }


    /**
     * @return array
     */
    public function getTargets(): array
    {
        return $this->targets;
    }

    /**
     * @param array $targets
     */
    public function setTargets(array $targets): void
    {
        $this->targets = $targets;
    }

    /**
     * @param Target $target
     */
    public function addTarget(Target $target)
    {
        $this->targets[] = $target->getId();
    }

    /**
     * @param Target $target
     */
    public function removeTarget(Target $target)
    {
        $index = array_search($target->getId(), $this->targets);

        if ($index !== false) {
            unset($this->targets[$index]);
        }
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return sprintf(
            'Action %s #%s performed on turn #%s',
            static::CLASS_NAME,
            $this->getId(),
            $this->turn->getId()
        );
    }
}