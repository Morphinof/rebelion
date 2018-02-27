<?php
/**
 * Created by PhpStorm.
 * User: Morphinof
 * Date: 10/01/2018
 * Time: 20:08
 */

namespace Rebelion\Traits;

/**
 * Trait PositionableTrait
 *
 * @package Rebelion\Traits
 */
trait PositionableTrait
{
    /**
     * @var integer
     *
     * @ORM\Column(name="position", type="integer")
     */
    protected $position = 0;

    /**
     * @return int
     */
    public function getPosition(): int
    {
        return $this->position;
    }

    /**
     * @param int $position
     */
    public function setPosition(int $position): void
    {
        $this->position = $position;
    }
}