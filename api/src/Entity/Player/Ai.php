<?php

namespace Rebelion\Entity\Player;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource
 * @ORM\Entity(repositoryClass="Rebelion\Repository\AiRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 * @package Rebelion\Entity
 */
class Ai extends Player
{
}
