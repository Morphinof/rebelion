<?php

namespace Rebelion\Enum;

use Rebelion\Abstracts\EnumAbstract;

class CharacteristicEnum extends EnumAbstract
{
    # String - max length 64
    # Primary
    const STRENGTH = 'strength';
    const DEXTERITY = 'dexterity';
    const INTELLECT = 'intellect';
    const CONSTITUTION = 'constitution';
    const LUCK = 'luck';

    # Secondary
    const HAND_SIZE = 'hand-size';
    const AP = 'ap';
    const DEFENCE = 'defence';
}