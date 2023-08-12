<?php

namespace Tests\Fixtures\App;

use Spatie\LaravelPackageTools\Package;

class ResourceServiceProvider extends \Filament\PluginServiceProvider
{
    //public static string $name = 'resources';

    protected array $resources = [
        //PageResource::class
    ];

    public function configurePackage(Package $package): void
    {
        $package->name('resources');
    }
}