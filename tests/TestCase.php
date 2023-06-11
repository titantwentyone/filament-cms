<?php

namespace Tests;

use BladeUI\Heroicons\BladeHeroiconsServiceProvider;
use BladeUI\Icons\BladeIconsServiceProvider;
use Filament\FilamentServiceProvider;
use Filament\Forms\FormsServiceProvider;
use Filament\Notifications\NotificationsServiceProvider;
use Filament\Support\SupportServiceProvider;
use Filament\Tables\TablesServiceProvider;
use Illuminate\Foundation\Testing\Concerns\InteractsWithViews;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\LivewireServiceProvider;
use RyanChandler\BladeCaptureDirective\BladeCaptureDirectiveServiceProvider;
use Tests\Fixtures\app\ResourceServiceProvider;
use Tests\Skeleton\app\Filament\Resources\PageResource;
use Tests\Skeleton\app\Models\Page;
use Tests\Skeleton\app\Models\Post;
use Titantwentyone\FilamentCMS\FilamentCMSServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    use RefreshDatabase;
    use InteractsWithViews;

    //protected $enablesPackageDiscoveries = true;

    protected function getPackageProviders($app)
    {
        return [
            BladeCaptureDirectiveServiceProvider::class,
            BladeHeroiconsServiceProvider::class,
            BladeIconsServiceProvider::class,
            FilamentServiceProvider::class,
            FormsServiceProvider::class,
            LivewireServiceProvider::class,
            NotificationsServiceProvider::class,
            SupportServiceProvider::class,
            TablesServiceProvider::class,
            ResourceServiceProvider::class,
            FilamentCMSServiceProvider::class
        ];
    }

    protected function defineDatabaseMigrations()
    {
        $this->loadMigrationsFrom(__DIR__.'/Fixtures/database/migrations');
        $this->loadLaravelMigrations();
    }

    protected function defineEnvironment($app)
    {

        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);

        $app['config']->set('filament-cms.models', [
            Page::class,
            Post::class
        ]);

        $app['config']->set('view.paths', array_merge(
            $app['config']->get('view.paths'),
            [
                __DIR__.'/Fixtures/resources/views',
//                __DIR__.'/../vendor/filament/filament/resources/views'
            ],

        ));

        $app['config']->set('livewire.view_path', __DIR__ . '/resources/views/livewire');

        //$app['config']->set('blade-heroicons.prefix', 'heroicon');

        //$app['config']->set('blade-heroicons.sets.herocions.prefix', 'heroicon');

//        $app['config']->set('livewire.class_namespace', '\\App\\Http\\Livewire');

        $app['config']->set('filament.resources.namespace', 'Tests\\Fixtures\\app\\Filament\\Resources');
        $app['config']->set('filament.resources.path', __DIR__.'/../Fixtures/app/Filament/Resources');
        $app['config']->set('filament.resources.register', [
            PageResource::class
        ]);

        //dump($app['config']->get('filament'));
//        dump($app['config']);
    }

    public function ignorePackageDiscoveriesFrom()
    {
        return ['akaunting/laravel-money'];
    }

    public static function applicationBasePath()
    {
        return __DIR__.'/Skeleton';
    }


}
