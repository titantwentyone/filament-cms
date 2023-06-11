<?php

namespace Titantwentyone\FilamentCMS;

use Filament\PluginServiceProvider;
use Spatie\LaravelPackageTools\Package;
use Titantwentyone\FilamentCMS\Commands\MakeContent;

class FilamentCMSServiceProvider extends PluginServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('filament-cms')
            ->hasConfigFile()
            ->hasCommand(MakeContent::class)
            ->hasRoute('web');
    }
}