<?php
/**
 * Created by PhpStorm.
 * User: Morphinof
 * Date: 15/12/2017
 * Time: 00:55
 */

namespace Rebelion\Exceptions;

class UnknownActionTypeException extends \Exception
{
    public function __construct(string $actionType)
    {
        $message = sprintf('Unknown action type "%s"', $actionType);

        parent::__construct($message);
    }
}