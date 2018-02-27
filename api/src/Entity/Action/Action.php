<?php

namespace Rebelion\Entity\Action;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\DiscriminatorColumn;
use Doctrine\ORM\Mapping\DiscriminatorMap;
use Doctrine\ORM\Mapping\InheritanceType;
use Rebelion\Traits\ActionTrait;

/**
 * Class ActionAbstract
 *
 * @ORM\Entity(repositoryClass="Rebelion\Repository\ActionRepository")
 * @InheritanceType("SINGLE_TABLE")
 * @DiscriminatorColumn(name="discr", type="string")
 * @DiscriminatorMap
 * (
 *      {
 *          "play-card" = "Rebelion\Entity\Action\PlayCard",
 *          "discard-card" = "Rebelion\Entity\Action\DiscardCard"
 *      }
 * )
 * @ORM\HasLifecycleCallbacks()
 *
 * @package Rebelion\Abstracts
 */
abstract class Action
{
    use ActionTrait;
}