<?php
/**
 * Created by PhpStorm.
 * User: Morphinof
 * Date: 13/12/2017
 * Time: 20:16
 */

namespace Rebelion\Interfaces;

use Rebelion\Entity\Combat;
use Rebelion\Entity\Effect\ProxyEffect;

interface EffectInterface
{
    /**
     * Resolve function contains the effect logic
     *
     * @param Combat      $combat
     * @param ProxyEffect $proxy
     * @param array       $targets
     *
     * @return bool
     */
    function resolve(Combat $combat, ProxyEffect $proxy, array &$targets): bool;
}