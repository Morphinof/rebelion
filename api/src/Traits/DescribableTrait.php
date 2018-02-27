<?php
/**
 * Created by PhpStorm.
 * User: Morphinof
 * Date: 09/12/2017
 * Time: 14:12
 */

namespace Rebelion\Traits;

use Doctrine\ORM\Mapping as ORM;

/**
 * Trait DescribableTrait
 *
 * @package Rebelion\Traits
 */
trait DescribableTrait
{
    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    protected $description;

    /**
     * @return null|string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }
}