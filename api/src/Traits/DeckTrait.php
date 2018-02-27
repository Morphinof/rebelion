<?php
/**
 * Created by PhpStorm.
 * User: Morphinof
 * Date: 04/01/2018
 * Time: 19:19
 */

namespace Rebelion\Traits;

use Rebelion\Enum\DeckStateEnum;

/**
 * Trait DeckTrait
 *
 * @package Rebelion\Traits
 */
trait DeckTrait
{
    use PileTrait;

    /**
     * Current deck state.
     *
     * @var string
     *
     * @ORM\Column(name="phase", type="string", length=64)
     */
    protected $state = DeckStateEnum::DRAFT;

    /**
     * @return string
     */
    public function getState(): string
    {
        return $this->state;
    }

    /**
     * @param string $state
     */
    public function setState(string $state): void
    {
        $this->state = $state;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->name;
    }
}