<?php

namespace Rebelion\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Rebelion\Traits\RaceTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\{
    Annotation as Gedmo
};
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource
 * @ORM\Entity(repositoryClass="Rebelion\Repository\RaceRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 * @package Rebelion\Entity
 */
class Race
{
    use RaceTrait;
}
