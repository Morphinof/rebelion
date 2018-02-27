<?php
/**
 * Created by PhpStorm.
 * User: Morphinof
 * Date: 10/12/2017
 * Time: 13:25
 */

namespace Rebelion\Traits;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\{
    Annotation as Gedmo
};
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Trait CardTrait
 *
 * @package Rebelion\Traits
 */
trait CardTrait
{
    use SlugableNameTrait;
    use DescribableTrait;
    use EntityTrait;

    /**
     * @var int
     * @Assert\NotBlank()
     *
     * @ORM\Column(name="cost", type="integer", nullable=false)
     */
    protected $cost = 0;

    /**
     * @var bool
     * @Assert\NotNull()
     *
     * @ORM\Column(name="replace_on_discard", type="boolean", nullable=false)
     */
    protected $replaceOnDiscard = false;

    /**
     * @return int
     */
    public function getCost(): int
    {
        return $this->cost;
    }

    /**
     * @param int $cost
     */
    public function setCost(int $cost): void
    {
        $this->cost = $cost;
    }

    /**
     * @return bool
     */
    public function isReplaceOnDiscard(): bool
    {
        return $this->replaceOnDiscard;
    }

    /**
     * @param bool $replaceOnDiscard
     */
    public function setReplaceOnDiscard($replaceOnDiscard): void
    {
        $this->replaceOnDiscard = (bool) $replaceOnDiscard;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $class = explode('\\', $this->class);

        return $class[count($class)];
    }
}