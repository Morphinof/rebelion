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
 * Effect class Leech
 *
 * @EffectAnnotation
 * (
 *      name="Leech",
 *      description="The player of this effect attack the target for X and heals X HP based on value",
 *      min="1",
 *      max="999",
 *      step="1",
 *      default=10,
 *      targetMode="x1",
 *      targetType="target"
 * )
 * @package Rebelion\Effect
 */
final class Leech extends EffectAbstract
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
        $player = $combat->getCurrentTurn()->getPlayer();

        if (!empty($targets)) {
            /** @var Target $target */
            foreach ($targets as $target) {
                $characteristics = $target->getPlayer()->getCharacteristics();
                $value           = $proxy->getParameters()['value'] ?? $proxy->getParameters()['default'];
                $leech           = (int)$value + $characteristics->getStrength();

                $characteristics->alterCharacteristic('hp', -$leech);
                $player->getPlayer()->getCharacteristics()->alterCharacteristic('hp', $leech);
            }

            return true;
        }

        return false;
    }
}
