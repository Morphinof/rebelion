<?php
/**
 * Created by PhpStorm.
 * User: Morphinof
 * Date: 15/12/2017
 * Time: 00:55
 */

namespace Rebelion\Exceptions;

use Rebelion\Entity\Effect\ProxyEffect;

class EffectResolveException extends \Exception
{
    public function __construct(ProxyEffect $effect, $message, \Exception $exception = null)
    {
        $message = sprintf(
            'ProxyEffect #%s %s (%s) has failed : %s',
            $effect->getId(),
            $effect->getParent()->getId(),
            $effect->getParent()->getName(),
            $message
        );

        if ($exception !== null) {
            $message = sprintf('%s with exception : %s', $message, $exception->getMessage());
        }

        parent::__construct($message);
    }
}