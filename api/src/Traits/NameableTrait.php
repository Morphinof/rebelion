<?php
/**
 * Created by PhpStorm.
 * User: Morphinof
 * Date: 09/12/2017
 * Time: 14:09
 */

namespace Rebelion\Traits;

use Doctrine\ORM\Mapping as ORM;

/**
 * Trait NameableTrait
 *
 * @see     Don't forget to add UniqueEntity and Assert annotations to entities that use this trait
 *
 * @package Rebelion\Traits
 */
trait NameableTrait
{
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     * @Assert\NotBlank()
     */
    protected $name;

    /**
     * @return null|string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }
}