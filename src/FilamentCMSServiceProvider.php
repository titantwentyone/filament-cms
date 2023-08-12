<?php

namespace Titantwentyone\FilamentCMS;

use Filament\PluginServiceProvider;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Storage;
use Spatie\LaravelPackageTools\Package;
use Titantwentyone\FilamentCMS\Commands\Composites\StubHandler;
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

    public function packageBooted(): void
    {
        //dump(app()->config);
        app()->config['filesystems.disks.filament_cms_stubs'] = [
            'driver' => 'local',
            'root' => __DIR__.'/../../stubs'
        ];

        app()->bind(StubHandler::class, function(Application $app) {
            return new StubHandler([
                'models' => Storage::build([
                    'driver' => 'local',
                    'root' => app_path('/Models')
                ]),
                'migrations' => Storage::build([
                    'driver' => 'local',
                    'root' => database_path('migrations')
                ]),
                'filament' => Storage::build([
                    'driver' => 'local',
                    'root' => base_path(config('filament.livewire.path'))
                ])
            ],
            Storage::disk('filament_cms_stubs'));
        });

        app()->bind('filament_cms_render', function($app) {

            $app->config['filesystems.disks.filament_cms_render'] = [
                'driver' => 'local',
                'root' => config('filament-cms.dynamic_render_location') ?? base_path('/storage/cms')
            ];

            return Storage::disk('filament_cms_render');
        });
    }
}