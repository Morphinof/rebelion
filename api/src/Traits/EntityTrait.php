<?php
/**
 * Created by PhpStorm.
 * User: Morphinof
 * Date: 16/12/2017
 * Time: 09:21
 */

namespace Rebelion\Traits;

use Doctrine\ORM\Mapping as ORM;

/**
 * Trait EntityTrait
 *
 * @see     Don't forget to add @ORM\HasLifecycleCallbacks to entities that use this trait
 * @package Rebelion\Traits]
 */
trait EntityTrait
{
    use CreatedUpdatedTrait;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @return null|integer
     */
    public function getId(): ?int
    {
        return $this->id;
    }
}