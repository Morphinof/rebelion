<?php

namespace Rebelion\Entity\Container;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Rebelion\Traits\PositionableTrait;
use Rebelion\Traits\ProxyCardTrait;
use Symfony\Component\Validator\{
    Constraints as Assert
};
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ApiResource
 * @ORM\Entity(repositoryClass="Rebelion\Repository\ProxyCardRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class ProxyCard
{
    const CLASS_NAME = 'ProxyCard';

    use ProxyCardTrait;
    use PositionableTrait;
}
