<?php

namespace Rebelion\Entity\Container;

use Rebelion\Abstracts\ProxyEffectContainerAbstract;
use Rebelion\Traits\CardTrait;
use Doctrine\ORM\Mapping\DiscriminatorColumn;
use Doctrine\ORM\Mapping\DiscriminatorMap;
use Doctrine\ORM\Mapping\InheritanceType;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\{
    Constraints as Assert
};

/**
 * @ORM\Entity(repositoryClass="Rebelion\Repository\CardRepository")
 * @UniqueEntity(
 *     "name",
 *      message="This name is already taken"
 * )
 * @InheritanceType("JOINED")
 * @DiscriminatorColumn(name="discr", type="string")
 * @DiscriminatorMap({
 *      "card" = "Card",
 *      "proxy" = "ProxyCard"
 * })
 * @ORM\HasLifecycleCallbacks()
 */
class Card extends ProxyEffectContainerAbstract
{
    const CLASS_NAME = 'Card';

    use CardTrait;
}
