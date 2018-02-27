<?php

namespace Rebelion\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Rebelion\Traits\UserTrait;

/**
 * @ApiResource
 * @ORM\Entity(repositoryClass="Rebelion\Repository\UserRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 * @package Rebelion\Entity
 */
class User
{
    use UserTrait;
}
