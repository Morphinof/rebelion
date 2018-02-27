<?php
/**
 * Created by PhpStorm.
 * User: Morphinof
 * Date: 15/12/2017
 * Time: 00:55
 */

namespace Rebelion\Exceptions;

class TurnFailedException extends \Exception
{
    public function __construct(\Exception $exception)
    {
        $message = sprintf('Failed to persist turn :<br />%s', $exception->getMessage());

        parent::__construct($message);
    }
}