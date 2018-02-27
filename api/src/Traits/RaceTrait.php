<?php
/**
 * Created by PhpStorm.
 * User: Morphinof
 * Date: 09/12/2017
 * Time: 14:30
 */

namespace Rebelion\Traits;

use Doctrine\ORM\Mapping as ORM;
use Rebelion\Entity\Characteristics;

/**
 * Trait RaceTrait
 *
 * @package Rebelion\Traits
 */
trait RaceTrait
{
    use SlugableNameTrait;
    use DescribableTrait;
    use EntityTrait;

    /**
     * RaceTrait constructor.
     */
    public function __construct()
    {
        $this->characteristics = new Characteristics();
    }

    /**
     * @var Characteristics
     *
     * @ORM\ManyToOne(targetEntity="Rebelion\Entity\Characteristics", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="characteristics_id", referencedColumnName="id", nullable=false)
     */
    protected $characteristics;

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