<?php

namespace Rebelion\Entity\Pile;

use ApiPlatform\Core\Annotation\ApiResource;
use Rebelion\Abstracts\CardContainerAbstract;
use Rebelion\Traits\DeckTrait;
use Rebelion\Traits\DescribableTrait;
use Rebelion\Traits\NameableTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource
 * @ORM\Entity(repositoryClass="Rebelion\Repository\DeckRepository")
 * @UniqueEntity(
 *     "name",
 *      message="This name is already taken"
 * )
 * @ORM\HasLifecycleCallbacks()
 *
 * @package Rebelion\Entity
 */
class Deck extends CardContainerAbstract
{
    use NameableTrait;
    use DescribableTrait;
    use DeckTrait;
}
