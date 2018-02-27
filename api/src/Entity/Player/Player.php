<?php

namespace Rebelion\Entity\Player;

use Doctrine\ORM\Mapping as ORM;
use Rebelion\Entity\Characteristics;
use Rebelion\Entity\Race;
use Rebelion\Enum\IconEnum;
use Rebelion\Traits\PlayerTrait;

/**
 * Class PlayerAbstract
 *
 * @ORM\Entity(repositoryClass="Rebelion\Repository\PlayerRepository")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap
 * (
 *      {
 *          "human" = "Rebelion\Entity\Player\Human",
 *          "ai" = "Rebelion\Entity\Player\Ai"
 *      }
 * )
 * @ORM\HasLifecycleCallbacks()
 *
 * @package Rebelion\Abstracts
 */
abstract class Player
{
    use PlayerTrait;

    /**
     * Player constructor.
     *
     * @param null      $alias
     * @param Race|null $race
     *
     * @throws \ReflectionException
     */
    public function __construct($alias = null, Race $race = null)
    {
        $this->alias = $alias;
        $this->race  = $race;

        if ($race !== null) {
            $this->characteristics->setCharacteristicsFromRace($race);
        } else {
            $this->characteristics = new Characteristics();
        }

        $this->autoSetIcon();
    }

    /**
     * Returns player type
     *
     * @return string
     */
    public function getType(): string
    {
        $class = explode('\\', static::class);

        return $class[count($class) - 1];
    }

    /**
     * Auto set the icon function of the static::class value
     */
    private function autoSetIcon(): void
    {
        switch (static::class) {
            case Human::class:
                $this->icon = IconEnum::HUMAN;
                break;
            case Ai::class:
                $this->icon = IconEnum::AI;
                break;
        }
    }
}
