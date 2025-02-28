<?php

namespace Cainy\Vessel\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Cainy\Vessel\LaravelOci
 */
class LaravelOci extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Cainy\Vessel\LaravelOci::class;
    }
}
