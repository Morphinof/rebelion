<?php
/**
 * Created by PhpStorm.
 * User: Morphinof
 * Date: 12/01/2018
 * Time: 20:04
 */

namespace Rebelion\Entity\Player;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Human
 *
 * @ORM\Entity(repositoryClass="Rebelion\Repository\HumanRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 * @package Rebelion\Entity
 */
class Human extends Player
{
}