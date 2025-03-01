<?php

namespace Cainy\Dockhand\Commands;

use Cainy\Dockhand\Support\JwtService;
use Exception;
use Illuminate\Console\Command;
use Lcobucci\JWT\Builder;

class NotifyTokenCommand extends Command
{
    public $signature = 'dockhand:notify-token';

    public $description = 'Create the authentication token for notifying the application of registry events';

    public function handle(JwtService $jwtService): int
    {
        $token = $jwtService->createToken(function (Builder $builder) {
            return $builder
                ->issuedAt(config('dockhand.authority_name'))
                ->permittedFor(config('dockhand.registry_name'))
                ->withClaim('access', 'notify');
        });

        $this->info('Generated new authentication token:');
        $this->newLine();
        $this->line($token->toString());

        return self::SUCCESS;
    }
}
