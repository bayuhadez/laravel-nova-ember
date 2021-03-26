<?php

namespace App\Interfaces;

interface RoundableInterface
{
    const ROUNDING_UP = 1;
    const ROUNDING_DOWN = 2;
    const ROUNDING_NORMAL = 3;

    /**
     * Allowed rounding values
     */
    const ROUNDING_VALUES = [
        '0.01',
        '0.1',
        '0.5',
        '1',
        '5',
        '10',
        '50',
        '100'
    ];

}
