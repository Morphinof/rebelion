<?php

namespace Rebelion\Entity;

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
 * @ORM\Entity(repositoryClass="Rebelion\Repository\CardCategoryRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class CardCategory
{
    use EntityTrait;
    use SlugableNameTrait;
}
