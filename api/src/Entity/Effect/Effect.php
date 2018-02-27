<?php

namespace Rebelion\Entity\Effect;

use ApiPlatform\Core\Annotation\ApiResource;
use Rebelion\Traits\EffectTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\{
    Constraints as Assert
};

/**
 * @ApiResource
 * @ORM\Entity(repositoryClass="Rebelion\Repository\EffectRepository")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(
 *     "name",
 *      message="This name is already taken"
 * )
 *
 * @package Rebelion\Entity
 */
class Effect
{
    use EffectTrait;
}
