<?php
/**
 * Created by PhpStorm.
 * User: Morphinof
 * Date: 09/12/2017
 * Time: 14:15
 */

namespace Rebelion\Traits;

use Doctrine\ORM\Mapping as ORM;

use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Trait SlugableNameTrait
 *
 * Don't forget to add UniqueEntity annotation to entities that use this trait
 *
 * @package Rebelion\Traits
 */
trait SlugableNameTrait
{
    use NameableTrait;

    /**
     * @var string
     *
     * @Gedmo\Slug(
     *      fields={"name"}
     * )
     * @ORM\Column(name="slug", type="string", length=255, unique=true)
     */
    protected $slug;

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

}