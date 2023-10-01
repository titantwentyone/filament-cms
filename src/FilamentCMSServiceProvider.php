<?php

namespace Titantwentyone\FilamentCMS;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Symfony\Component\Process\Process;
use Titantwentyone\FilamentCMS\Commands\Composites\StubHandler;
use Titantwentyone\FilamentCMS\Commands\MakeContent;
use Titantwentyone\FilamentCMS\Contracts\Content;
use Titantwentyone\FilamentCMS\Controllers\ContentController;
use Titantwentyone\FilamentCMS\Domain\Process\AssetCompilationProcess;
use Titantwentyone\FilamentCMS\Domain\Render\RenderStorage;
use Titantwentyone\FilamentCMS\Filament\Resources\Concerns\RendersView;
use Titantwentyone\FilamentCMS\Filament\Resources\PartResource;

class FilamentCMSServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('filament-cms')
            ->hasConfigFile()
            ->hasCommand(MakeContent::class)
            ->hasRoute('web')
            ->hasMigration('create_parts_table')
            ->runsMigrations();

        //Livewire::component('titantwentyone.filament-c-m-s.filament.resources.part-resource.pages.edit-part', PartResource\Pages\EditPart::class);
    }

    public function packageBooted(): void
    {
        $this->registerFilesystems();

        $this->registerBindings();

        $this->registerLogChannels();

        $this->registerArrayMacros();

    }

    public function packageRegistered(): void
    {
        parent::packageRegistered();

        app()->bind('content_routes', function($app) {
            return collect(config('filament-cms.models'))->mapWithKeys(fn($class) => [$class => $class::$prefix]);
        });
    }

    private function registerBindings(): void
    {
        app()->bind(StubHandler::class, function(Application $app) {

            return new StubHandler(
                [
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
                        'root' => base_path(config('filament.resources.path'))
                    ])
                ],
                Storage::build([
                    'driver' => 'local',
                    'root' => realpath(__DIR__.'/../stubs')
                ])
            );
        });

        app()->bind(RenderStorage::class, RenderStorage::class);

        app()->bind(Process::class, Process::class);

        app()->bind(AssetCompilationProcess::class, function($app) {
            $command = config('filament-cms.compilation_command') ?? 'npm run build';
            $command = explode(" ", $command);
            $process = app()->makeWith(Process::class, ['command' => $command]);
            return new AssetCompilationProcess($process);
        });
    }

    private function registerArrayMacros(): void
    {
        Arr::macro('normalize', function(array $array) {

            return collect($array)->mapWithKeys(function($item, $key) {
                if(is_numeric($key)) {
                    return [$item => $item];
                } else {
                    return [$key => $item];
                }
            })->toArray();
        });
    }

    private function registerFilesystems(): void
    {
        app()->config['filesystems.disks.filament_cms_stubs'] = [
            'driver' => 'local',
            'root' => __DIR__.'/../stubs'
        ];
    }

    /**
     * @return void
     */
    private function registerLogChannels(): void
    {
        app()->config['logging.channels.filament_cms_dynamic_render'] = [
            'driver' => 'single',
            'path' => storage_path('logs/filament_cms_dynamic_render.log')
        ];
    }
}