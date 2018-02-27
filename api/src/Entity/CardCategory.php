<?php

namespace Rebelion\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Rebelion\Traits\EntityTrait;
use Rebelion\Traits\SlugableNameTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\{
    Annotation as Gedmo
};
use Symfony\Component\Validator\{
    Constraints as Assert
};


/**
 * @ApiResource
 * @ORM\Entity(repositoryClass="Rebelion\Repository\CardCategoryRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class CardCategory
{
    use EntityTrait;
    use SlugableNameTrait;
}
