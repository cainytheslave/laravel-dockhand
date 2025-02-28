<?php

namespace Cainy\Vessel;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Cainy\Vessel\Commands\VesselCommand;
use Cainy\Vessel\Support\JwtService;

class VesselServiceProvider extends PackageServiceProvider
{
    public function register(): void
    {
        parent::register();

        $this->app->singleton(JwtService::class, function () {
            return new JwtService();
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
