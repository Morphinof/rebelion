<?php

namespace Rebelion\Enum;

use Rebelion\Abstracts\EnumAbstract;

/**
 * Class TargetModeEnum
 *
 * @package Rebelion\Enum
 */
class TargetModeEnum extends EnumAbstract
{
    const SELF = 'self';
    const X1 = 'x1';
    const X2 = 'x2';
    const X3 = 'x3';
    const ALL = 'all';
    const EVERYONE = 'everyone';

    /**
     * @return array
     */
    public static function __toAssoc()
    {
        return [
            self::SELF     => 'self',
            self::X1       => '1_target',
            self::X2       => '2_targets',
            self::X3       => '3_targets',
            self::ALL      => 'all_enemies',
            self::EVERYONE => 'everyone',
        ];
    }

    /**
     * @return array
     */
    public static function __toChoice()
    {
        return [
            'self'        => self::SELF,
            '1_target'    => self::X1,
            '2_targets'   => self::X2,
            '3_targets'   => self::X3,
            'all_enemies' => self::ALL,
            'everyone'    => self::EVERYONE,
        ];
    }
}