<?php

namespace Rebelion\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Rebelion\Traits\CombatTrait;

/**
 * @ApiResource
 * @ORM\Entity(repositoryClass="Rebelion\Repository\CombatRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 * @package Rebelion\Entity
 */
class Combat
{
    use CombatTrait;
}
