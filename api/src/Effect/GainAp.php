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
use Rebelion\Enum\CharacteristicEnum;
use Rebelion\Event\Combat\DeadTarget;

/**
 * Effect class GainAp
 *
 * @EffectAnnotation
 * (
 *      name="Gain Action Points",
 *      description="The player of this effect gain X AP based on value",
 *      min="2",
 *      max="20",
 *      step="1",
 *      default=2,
 *      targetMode="self",
 *      targetType="target",
 * )
 * @package Rebelion\Effect
 */
final class GainAp extends EffectAbstract
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
                $apAlteration  = (int)($proxy->getParameters()['value'] ?? $proxy->getParameters()['default']);

                $target->getPlayer()->getCharacteristics()->alterCharacteristic(CharacteristicEnum::AP, $apAlteration);
            }

            return true;
        }

        return false;
    }
}
