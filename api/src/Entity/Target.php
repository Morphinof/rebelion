<?php

namespace Rebelion\Entity;

use Doctrine\ORM\Mapping as ORM;
use Rebelion\Traits\TargetTrait;

/**
 * Target
 *
 * @ORM\Entity(repositoryClass="Rebelion\Repository\TargetRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Target
{
    use TargetTrait;
}
