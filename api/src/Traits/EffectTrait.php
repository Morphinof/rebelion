<?php
/**
 * Created by PhpStorm.
 * User: Morphinof
 * Date: 10/12/2017
 * Time: 13:21
 */

namespace Rebelion\Traits;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\{
    Annotation as Gedmo
};
use Rebelion\Enum\TargetModeEnum;

/**
 * Trait EffectTrait
 *
 * @package Rebelion\Traits
 */
trait EffectTrait
{
    use NameableTrait;
    use DescribableTrait;
    use EntityTrait;

    /**
     * EffectTrait constructor.
     *
     * @param array $parameters
     */
    public function __construct($parameters = [])
    {
        $this->parameters = $parameters;
    }

    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"class", "name"})
     * @ORM\Column(name="slug", type="string", length=255, unique=true)
     */
    protected $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="class", type="string", length=128, nullable=false)
     */
    protected $class;

    /**
     * @var string
     *
     * @ORM\Column(name="target_mode", type="string", length=24, nullable=false)
     */
    protected $targetMode;

    /**
     * @var string
     *
     * @ORM\Column(name="target_type", type="string", length=24, nullable=false)
     */
    protected $targetType;

    /**
     * @var array|null
     *
     * @ORM\Column(name="parameters", type="json", nullable=true)
     */
    protected $parameters = null;

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     */
    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }

    /**
     * @return string|null
     */
    public function getClass(): ?string
    {
        return $this->class;
    }

    /**
     * @param string $class
     */
    public function setClass(string $class): void
    {
        $this->class = $class;
    }

    /**
     * @return string
     */
    public function getClassName(): string
    {
        $class = explode('\\', $this->class);

        return $class[count($class) - 1];
    }

    /**
     * @param bool $asInt
     *
     * @return int|string
     */
    public function getTargetMode($asInt = false)
    {
        if ($asInt) {
            switch ($this->targetMode) {
                case TargetModeEnum::SELF:
                    return 1;
                case TargetModeEnum::X1:
                    return 1;
                case TargetModeEnum::X2:
                    return 2;
                case TargetModeEnum::X3:
                    return 3;
                case TargetModeEnum::ALL:
                    return 4;
                case TargetModeEnum::EVERYONE:
                    return 5;
                default:
                    return 0;
            }
        }

        return $this->targetMode;
    }

    /**
     * @param string $targetMode
     */
    public function setTargetMode(string $targetMode): void
    {
        $this->targetMode = $targetMode;
    }

    /**
     * @return string
     */
    public function getTargetType(): string
    {
        return $this->targetType;
    }

    /**
     * @param string $targetType
     */
    public function setTargetType(string $targetType): void
    {
        $this->targetType = $targetType;
    }

    /**
     * @return array|null
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @param array $parameters
     */
    public function setParameters(array $parameters): void
    {
        $this->parameters = $parameters;
    }

    /**
     * @return string
     */
    public function __toDump()
    {
        return sprintf(
            '%s - %s (%s)',
            implode(', ', $this->parameters),
            $this->name,
            $this->id
        );
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return sprintf('%s', $this->name);
    }

    /**
     * @return array
     */
    public function __toJson(): array
    {
        return [
            'id'    => $this->id,
            'class' => $this->class,
            'slug'  => $this->slug,
            'name'  => $this->name,
        ];
    }
}