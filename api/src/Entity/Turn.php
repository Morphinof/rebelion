<?php

namespace Rebelion\Entity;

use Rebelion\Traits\EntityTrait;
use Rebelion\Traits\TurnTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Turn
 *
 * @ORM\Entity(repositoryClass="Rebelion\Repository\CombatTurnRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 * @package Rebelion\Entity
 */
class Turn
{
    use TurnTrait;
}
