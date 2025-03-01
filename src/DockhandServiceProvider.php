<?php

namespace Cainy\Dockhand;

use Cainy\Dockhand\Services\TokenService;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Cainy\Dockhand\Commands\NotifyTokenCommand;

class DockhandServiceProvider extends PackageServiceProvider
{
    public function register(): void
    {
        parent::register();

        $this->app->singleton(TokenService::class, function() {
            return new TokenService(
                config('dockhand.jwt_private_key'),
                config('dockhand.jwt_public_key'));
        });
    }

    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-dockhand')
            ->hasConfigFile()
            ->hasCommand(NotifyTokenCommand::class);
    }
}
