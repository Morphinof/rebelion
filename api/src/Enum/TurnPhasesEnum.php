<?php

namespace Rebelion\Enum;

use Rebelion\Abstracts\EnumAbstract;

class TurnPhasesEnum extends EnumAbstract
{
    # String - max length 64
    const INIT = 'init';
    const DRAW = 'draw';
    const MAIN = 'main';
    const END = 'end';

    /**
     * Turn phases that are actions
     *
     * @return array
     */
    public static function __actions(): array
    {
        return [
            self::DRAW,
            self::MAIN,
            self::END,
        ];
    }
}