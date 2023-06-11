<?php

namespace Tests\Fixtures\app;

use Spatie\LaravelPackageTools\Package;
use Tests\Skeleton\app\Filament\Resources\PageResource;

class ResourceServiceProvider extends \Filament\PluginServiceProvider
{
    //public static string $name = 'resources';

    protected array $resources = [
        PageResource::class
    ];

    public function configurePackage(Package $package): void
    {
        $package->name('resources');
    }
}