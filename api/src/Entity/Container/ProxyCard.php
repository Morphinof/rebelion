<?php

namespace Rebelion\Entity\Container;

use Doctrine\ORM\Mapping as ORM;
use Rebelion\Abstracts\ProxyEffectContainerAbstract;
use Rebelion\Traits\PositionableTrait;
use Rebelion\Traits\ProxyCardTrait;
use Symfony\Component\Validator\{
    Constraints as Assert
};
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="Rebelion\Repository\ProxyCardRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class ProxyCard
{
    const CLASS_NAME = 'ProxyCard';

    use ProxyCardTrait;
    use PositionableTrait;
}
