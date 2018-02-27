<?php

namespace Rebelion\Enum;

use Rebelion\Abstracts\EnumAbstract;

/**
 * Class ActionTypeEnum
 *
 * @package Rebelion\Enum
 */
class ActionTypeEnum extends EnumAbstract
{
    const PLAY_CARD = 'play_card';
    const USE_OBJECT = 'use_object';
    const END_TURN = 'end_turn';
    const END_COMBAT = 'end_combat';
}