<?php
/**
 * Created by PhpStorm.
 * User: Morphinof
 * Date: 14/01/2018
 * Time: 10:10
 */

namespace Rebelion\Annotations;

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 * @Target("CLASS")
 */
final class EffectAnnotation extends Annotation
{
    /** @var string $name */
    public $name = null;

    /** @var string $description */
    public $description = null;

    /** @var int $min */
    public $min = null;

    /** @var int $max */
    public $max = null;

    /** @var string $step */
    public $step = null;

    /** @var mixed $default */
    public $default = null;

    /** @var string $targetMode */
    public $targetMode = null;

    /** @var string $targetType */
    public $targetType = null;

    /**
     * @return array
     */
    public function __toArray()
    {
        return [
            'name'        => $this->name,
            'description' => $this->description,
            'min'         => $this->min,
            'max'         => $this->max,
            'step'        => $this->step,
            'default'     => $this->default,
            'targetMode'  => $this->targetMode,
            'targetType'  => $this->targetType,
        ];
    }
}