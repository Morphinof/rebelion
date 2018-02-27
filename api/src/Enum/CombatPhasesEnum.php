<?php

namespace Rebelion\Enum;

use Rebelion\Abstracts\EnumAbstract;

class CombatPhasesEnum extends EnumAbstract
{
    # String - max length 64
    const INIT = 'init';
    const START_TURN = 'start_turn';
    const PLAY_TURN = 'play_turn';
    const END_TURN = 'end_turn';
    const END_COMBAT = 'end_combat';
    const SLEEPING = 'sleeping';

    /**
     * Combat phases that are actions
     *
     * @return array
     */
    public static function __actions(): array
    {
        return [
            self::START_TURN,
            self::PLAY_TURN,
            self::END_TURN,
            self::END_COMBAT,
        ];
    }
}