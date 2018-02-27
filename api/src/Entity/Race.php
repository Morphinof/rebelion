<?php

namespace Rebelion\Entity;

use Rebelion\Traits\RaceTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\{
    Annotation as Gedmo
};
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Race
 *
 * @ORM\Entity(repositoryClass="Rebelion\Repository\RaceRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 * @package Rebelion\Entity
 */
class Race
{
    use RaceTrait;
}
