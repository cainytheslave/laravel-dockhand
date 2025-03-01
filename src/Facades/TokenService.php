<?php

namespace Cainy\Dockhand\Facades;

use Closure;
use Illuminate\Support\Facades\Facade;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\UnencryptedToken;

/**
 * @method static Builder getBuilder()
 * @method static UnencryptedToken signToken(Builder $builder)
 * @method static bool validateToken(string $token, Closure $closure)
 *
 * @see \Cainy\Dockhand\Services\TokenService
 */
class TokenService extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Cainy\Dockhand\Services\TokenService::class;
    }
}
