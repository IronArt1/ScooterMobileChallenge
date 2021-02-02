<?php

namespace App\Interfaces\EventHandler;

/**
 * Interface EventHandler
 *
 * @package App\Interfaces\EventHandler
 */
interface EventHandlerInterface
{
    /**
     * Units of a coordinate are
     */
    public const UNITS_OF_COORDINATES = [
        'latitude',
        'longitude'
    ];

    /**
     * A holder of `get` is
     */
    public const GET_HOLDER = 'get';
}
