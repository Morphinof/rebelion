<?php
/**
 * Created by PhpStorm.
 * User: Morphinof
 * Date: 13/01/2018
 * Time: 12:33
 */

namespace Rebelion\Traits;

use Rebelion\Entity\Characteristics;
use Rebelion\Entity\Race;

/**
 * Trait PlayerTrait
 *
 * @package Rebelion\Traits
 */
trait PlayerTrait
{
    use EntityTrait;

    /**
     * @var string
     *
     * @ORM\Column(name="alias", type="string", length=255)
     */
    protected $alias;

    /**
     * @var string
     *
     * @ORM\Column(name="icon", type="string", length=255)
     */
    protected $icon;

    /**
     * @var Race
     *
     * @ORM\ManyToOne(targetEntity="Rebelion\Entity\Race")
     * @ORM\JoinColumn(name="race_id", referencedColumnName="id", nullable=false)
     */
    protected $race;

    /**
     * @var Characteristics
     *
     * @ORM\ManyToOne(targetEntity="Rebelion\Entity\Characteristics", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="characteristics_id", referencedColumnName="id", nullable=false)
     */
    protected $characteristics;

    /**
     * @return string
     */
    public function getAlias(): string
    {
        return $this->alias;
    }

    /**
     * @param string $alias
     */
    public function setAlias(string $alias): void
    {
        $this->alias = $alias;
    }

    /**
     * @return string
     */
    public function getIcon(): string
    {
        return $this->icon;
    }

    /**
     * @param string $icon
     */
    public function setIcon(string $icon): void
    {
        $this->icon = $icon;
    }

    /**
     * @return Race
     */
    public function getRace(): Race
    {
        return $this->race;
    }

    /**
     * @param Race $race
     */
    public function setRace(Race $race): void
    {
        $this->race = $race;
    }

    /**
     * @return Characteristics
     */
    public function getCharacteristics(): Characteristics
    {
        return $this->characteristics;
    }

    /**
     * @param Characteristics $characteristics
     */
    public function setCharacteristics(Characteristics $characteristics): void
    {
        $this->characteristics = $characteristics;
    }
}