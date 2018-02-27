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
 * Effect class Attack
 *
 * @EffectAnnotation
 * (
 *      name="Attack",
 *      description="The target of this effect takes X damages based on value",
 *      min="1",
 *      max="20",
 *      step="1",
 *      default=6,
 *      targetMode="x1",
 *      targetType="target",
 * )
 * @package Rebelion\Effect
 */
final class Attack extends EffectAbstract
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
                $playerAttack  = (int)($proxy->getParameters()['value'] ?? $proxy->getParameters()['default']);
                $playerAttack  += $player->getPlayer()->getCharacteristics()->getStrength();
                $targetDefence = $target->getPlayer()->getCharacteristics()->getDefence();
                $effective     = ($playerAttack - $targetDefence) < 0 ? 0 : ($playerAttack - $targetDefence);

                $target->getPlayer()->getCharacteristics()->alterCharacteristic('hp', -$effective);
                if ($targetDefence > 0) {
                    $removeDefence = ($playerAttack > $targetDefence ? $targetDefence : $playerAttack);
                    $target->getPlayer()->getCharacteristics()->alterCharacteristic(CharacteristicEnum::DEFENCE, -$removeDefence);
                }
            }

            return true;
        }

        return false;
    }
}
