<?php

namespace Rebelion\Entity;

use Doctrine\ORM\Mapping as ORM;
use Rebelion\Traits\UserTrait;

/**
 * Class User
 *
 * @ORM\Entity(repositoryClass="Rebelion\Repository\UserRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 * @package Rebelion\Entity
 */
class User
{
    use UserTrait;
}
