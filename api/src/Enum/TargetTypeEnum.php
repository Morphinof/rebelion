<?php

namespace Rebelion\Enum;

use Rebelion\Abstracts\EnumAbstract;

/**
 * Class TargetModeEnum
 *
 * @package Rebelion\Enum
 */
class TargetTypeEnum extends EnumAbstract
{
    const TARGETS = 'targets';
    const CARDS = 'cards';

    /**
     * @return array
     */
    public static function __toAssoc()
    {
        return [
            self::TARGETS => 'target',
            self::CARDS   => 'card',
        ];
    }

    /**
     * @return array
     */
    public static function __toChoice()
    {
        return [
            'targets' => self::TARGETS,
            'cards'   => self::CARDS,
        ];
    }
}