<?php
/**
 * Created by PhpStorm.
 * User: Morphinof
 * Date: 15/12/2017
 * Time: 00:55
 */

namespace Rebelion\Exceptions;

use Rebelion\Entity\Combat;

class NullCurrentPlayerException extends \Exception
{
    public function __construct(Combat $combat)
    {
        $message = sprintf('Combat #%s, Current player is null, have you use CombatService without calling setCurrentPlayer ?', $combat->getId());

        parent::__construct($message);
    }
}