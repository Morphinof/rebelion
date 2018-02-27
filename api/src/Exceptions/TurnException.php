<?php
/**
 * Created by PhpStorm.
 * User: Morphinof
 * Date: 15/12/2017
 * Time: 00:55
 */

namespace Rebelion\Exceptions;

use Rebelion\Entity\Turn;

class TurnException extends \Exception
{
    public function __construct(Turn $turn, $message, \Exception $exception = null)
    {
        if ($exception !== null) {
            $message = sprintf('%s has failed with exception : %s', $message, $exception->getMessage());

            dump($exception);
        }

        parent::__construct($message);
    }
}