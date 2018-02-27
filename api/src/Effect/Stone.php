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
use Rebelion\Entity\Pile\Hand;
use Rebelion\Entity\Pile\Draw;
use Rebelion\Entity\Pile\Discard;
use Rebelion\Entity\Target;
use Rebelion\Enum\CardStatusEnum;

/**
 * Effect class Stone
 *
 * @EffectAnnotation
 * (
 *      name="Stone",
 *      description="The target gets X cards stone in his hand",
 *      min="1",
 *      max="10",
 *      step="1",
 *      default=1,
 *      targetMode="x1",
 *      targetType="target"
 * )
 * @package Rebelion\Effect
 */
final class Stone extends EffectAbstract
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
                    $stoned = null;

                    $piles = [$target->getHand(), $target->getDraw(), $target->getDiscard()];
                    /** @var Hand|Draw|Discard $pile */
                    foreach ($piles as $pile) {
                        $stoned = $pile->getRandom();
                        if ($stoned !== null) {
                            break;
                        }
                    }

                    if ($stoned === null) {
                        continue;
                    }

                    if ($stoned->getStatus() !== CardStatusEnum::STONED) {
                        $stoned->setStatus(CardStatusEnum::STONED);
                    } else {
                        $i--;
                    }
                }
            }

            return true;
        }

        return false;
    }
}
