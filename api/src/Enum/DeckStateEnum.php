<?php

namespace Rebelion\Enum;

use Rebelion\Abstracts\EnumAbstract;

class DeckStateEnum extends EnumAbstract
{
    # String - max length 64
    const DRAFT = 'draft';
    const PUBLISHED = 'published';

    /**
     * Combat phases that are actions
     *
     * @return array
     */
    public static function __actions(): array
    {
        return [
            self::DRAFT,
            self::PUBLISHED,
        ];
    }
}