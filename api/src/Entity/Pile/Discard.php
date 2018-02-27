<?php

namespace Rebelion\Entity\Pile;

use Doctrine\ORM\Mapping as ORM;
use Rebelion\Abstracts\CardContainerAbstract;
use Rebelion\Traits\PileTrait;

/**
 * @ORM\Entity(repositoryClass="Rebelion\Repository\DiscardRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Discard extends CardContainerAbstract
{
    use PileTrait;
}
