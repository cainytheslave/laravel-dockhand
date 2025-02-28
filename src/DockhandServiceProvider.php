<?php

namespace Cainy\Dockhand;

use Cainy\Dockhand\Commands\DockhandCommand;
use Cainy\Dockhand\Support\JwtService;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class DockhandServiceProvider extends PackageServiceProvider
{
    public function register(): void
    {
        parent::register();

        $this->app->singleton(JwtService::class, function () {
            return new JwtService;
        });
    }

    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-Dockhand')
            ->hasConfigFile()
            ->hasCommand(DockhandCommand::class);
    }
}
