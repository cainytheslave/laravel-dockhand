<?php

namespace Cainy\Dockhand\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Cainy\Dockhand\Dockhand
 */
class LaravelOci extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Cainy\Dockhand\Dockhand::class;
    }
}
