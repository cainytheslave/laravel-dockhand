<?php

namespace Cainy\Vessel;

use Cainy\Vessel\Commands\VesselCommand;
use Cainy\Vessel\Support\JwtService;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class VesselServiceProvider extends PackageServiceProvider
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
            ->name('laravel-vessel')
            ->hasConfigFile()
            ->hasCommand(VesselCommand::class);
    }
}
