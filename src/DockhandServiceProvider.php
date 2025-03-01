<?php

namespace Cainy\Dockhand;

use Cainy\Dockhand\Services\ClaimService;
use Cainy\Dockhand\Services\JwtService;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Cainy\Dockhand\Commands\NotifyTokenCommand;

class DockhandServiceProvider extends PackageServiceProvider
{
    public function register(): void
    {
        parent::register();

        $this->app->singleton(JwtService::class, fn() => new JwtService());
    }

    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-dockhand')
            ->hasConfigFile()
            ->hasCommand(NotifyTokenCommand::class);
    }
}
