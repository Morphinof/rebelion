<?php

namespace Rebelion\Entity\Player;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Ai
 *
 * @ORM\Entity(repositoryClass="Rebelion\Repository\AiRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 * @package Rebelion\Entity
 */
class Ai extends Player
{
}
