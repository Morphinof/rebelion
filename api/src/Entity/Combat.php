<?php

namespace Rebelion\Entity;

use Doctrine\ORM\Mapping as ORM;
use Rebelion\Traits\CombatTrait;

/**
 * Class Combat
 *
 * @ORM\Entity(repositoryClass="Rebelion\Repository\CombatRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 * @package Rebelion\Entity
 */
class Combat
{
    use CombatTrait;
}
