<?php

namespace Rebelion\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Rebelion\Traits\TargetTrait;

/**
 * @ApiResource
 * @ORM\Entity(repositoryClass="Rebelion\Repository\TargetRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Target
{
    use TargetTrait;
}
