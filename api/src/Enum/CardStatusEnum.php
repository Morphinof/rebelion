<?php

namespace Rebelion\Enum;

use Rebelion\Abstracts\EnumAbstract;

/**
 * Class CardStatusEnum
 *
 * @package Rebelion\Enum
 */
class CardStatusEnum extends EnumAbstract
{
    # String - max length 64
    # Primary
    const PLAYABLE = 'playable';
    const STONED = 'stoned';
}