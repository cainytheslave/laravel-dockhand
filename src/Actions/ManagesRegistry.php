<?php

namespace Cainy\Dockhand\Actions;

use Cainy\Dockhand\Resources\Scope;
use Cainy\Dockhand\Services\JwtService;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Lcobucci\JWT\Token\Builder;

trait ManagesRegistry {

    /**
     * @throws GuzzleException
     */
    function isOnline(): bool
    {
        $token = JwtService::createRegistryToken(function (Builder $builder) {
            return $builder->withClaim('access', Scope::get());
        });

        return $this->get('/'); // TODO
    }

}
