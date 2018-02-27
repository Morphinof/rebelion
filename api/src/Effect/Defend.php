<?php
/**
 * Created by PhpStorm.
 * User: Morphinof
 * Date: 14/01/2018
 * Time: 10:39
 */

namespace Rebelion\Effect;

use Rebelion\Abstracts\EffectAbstract;
use Rebelion\Annotations\EffectAnnotation;
use Rebelion\Entity\Combat;
use Rebelion\Entity\Effect\ProxyEffect;
use Rebelion\Entity\Target;

/**
 * Effect class Attack
 *
 * @EffectAnnotation
 * (
 *      name="Defend",
 *      description="The target of this effect gains X defend based on value",
 *      min="1",
 *      max="20",
 *      step="1",
 *      default=6,
 *      targetMode="self",
 *      targetType="target"
 * )
 * @package Rebelion\Effect
 */
final class Defend extends EffectAbstract
{
    /**
     * The resolve function must be implemented by children
     *
     * @param Combat      $combat
     * @param ProxyEffect $proxy
     * @param array       $targets
     *
     * @return bool
     */
    public function resolve(Combat $combat, ProxyEffect $proxy, array &$targets): bool
    {
        if (!empty($targets)) {
            /** @var Target $target */
            foreach ($targets as $target) {
                $characteristics = $target->getPlayer()->getCharacteristics();
                $value           = $proxy->getParameters()['value'] ?? $proxy->getParameters()['default'];
                $defence         = (int)$value + $characteristics->getDexterity();

                $characteristics->alterCharacteristic('defence', $defence);
            }

            return true;
        }

        return false;
    }
}
