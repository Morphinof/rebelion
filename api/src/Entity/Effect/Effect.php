<?php

namespace Rebelion\Entity\Effect;

use Rebelion\Traits\EffectTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\{
    Constraints as Assert
};

/**
 * Class Effect
 *
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
