<?php

namespace Rebelion\Entity\Pile;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Rebelion\Abstracts\CardContainerAbstract;
use Rebelion\Traits\PileTrait;

/**
 * @ApiResource
 * @ORM\Entity(repositoryClass="Rebelion\Repository\DiscardRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Discard extends CardContainerAbstract
{
    use PileTrait;
}
