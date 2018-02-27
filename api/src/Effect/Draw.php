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
 * Effect class Draw
 *
 * @EffectAnnotation
 * (
 *      name="Draw",
 *      description="The target of this effect draws X cards based on value",
 *      min="1",
 *      max="10",
 *      step="1",
 *      default=1,
 *      targetMode="self",
 *      targetType="target"
 * )
 * @package Rebelion\Effect
 */
final class Draw extends EffectAbstract
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
                $value = $proxy->getParameters()['value'] ?? $proxy->getParameters()['default'];

                for ($i = 0; $i < (int)$value; $i++) {
                    $drawCard = $target->getDraw()->draw() ?? null;

                    if ($drawCard === null) {
                        continue;
                    }

                    $target->getHand()->pushCard($drawCard);
                }
            }

            return true;
        }

        return false;
    }
}
