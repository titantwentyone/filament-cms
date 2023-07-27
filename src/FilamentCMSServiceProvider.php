<?php

namespace Titantwentyone\FilamentCMS;

use Filament\PluginServiceProvider;
use Spatie\LaravelPackageTools\Package;
use Titantwentyone\FilamentCMS\Commands\MakeContent;
use Titantwentyone\FilamentCMS\Filament\Resources\PartResource;

class FilamentCMSServiceProvider extends PluginServiceProvider
{
    protected array $resources = [
        PartResource::class
    ];
    public function configurePackage(Package $package): void
    {
        $package->name('filament-cms')
            ->hasConfigFile()
            ->hasCommand(MakeContent::class)
            ->hasRoute('web')
            ->hasMigration('create_parts_table')
            ->runsMigrations();
    }
}