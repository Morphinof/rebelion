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
 * Effect class Steal
 *
 * @EffectAnnotation
 * (
 *      name="Steal",
 *      description="The player steals X cards from target hand",
 *      min="1",
 *      max="5",
 *      step="1",
 *      default=1,
 *      targetMode="x1",
 *      targetType="target"
 * )
 * @package Rebelion\Effect
 */
final class Steal extends EffectAbstract
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
                $value = $proxy->getParameters()['value'] ?? $proxy->getParameters()['default'];

                for ($i = 0; $i < (int)$value; $i++) {
                    $stolen = $target->getHand()->drawRandom() ?? null;

                    if ($stolen === null) {
                        continue;
                    }

                    $player->getHand()->pushCard($stolen);
                }
            }

            return true;
        }

        return false;
    }
}
