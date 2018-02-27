<?php
/**
 * Created by PhpStorm.
 * User: Morphinof
 * Date: 15/12/2017
 * Time: 00:55
 */

namespace Rebelion\Exceptions;

use Rebelion\Entity\Combat;

class CombatException extends \Exception
{
    public function __construct($message, Combat $combat = null, \Exception $exception = null)
    {
        if ($combat !== null) {
            if ($exception !== null) {
                $message = sprintf(
                    '%s | Combat %s exception : %s<br />%s',
                    $message,
                    $combat->getId(),
                    $exception->getMessage(),
                    $exception->getTraceAsString()
                );
            }
        }

        parent::__construct($message);
    }
}