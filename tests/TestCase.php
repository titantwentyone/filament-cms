<?php

namespace Tests;

use BladeUI\Heroicons\BladeHeroiconsServiceProvider;
use BladeUI\Icons\BladeIconsServiceProvider;
use Filament\Actions\ActionsServiceProvider;
use Filament\FilamentServiceProvider;
use Filament\Forms\FormsServiceProvider;
use Filament\Infolists\InfolistsServiceProvider;
use Filament\Notifications\NotificationsServiceProvider;
use Filament\Support\SupportServiceProvider;
use Filament\Tables\TablesServiceProvider;
use Filament\Widgets\WidgetsServiceProvider;
use Illuminate\Foundation\Testing\Concerns\InteractsWithViews;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\LivewireServiceProvider;
use RyanChandler\BladeCaptureDirective\BladeCaptureDirectiveServiceProvider;
use Tests\Fixtures\App\Models\PageWithArgs;
use Tests\Fixtures\App\Models\PageWithRootPrefix;
use Titantwentyone\FilamentCMS\FilamentCMSServiceProvider;
use Titantwentyone\FilamentCMS\PartPanelProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    use RefreshDatabase;
    use InteractsWithViews;

    //protected $enablesPackageDiscoveries = true;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs(\Tests\Fixtures\App\Models\User::factory()->create());
    }

    protected function getPackageProviders($app)
    {
        return [
            ActionsServiceProvider::class,
            BladeCaptureDirectiveServiceProvider::class,
            BladeHeroiconsServiceProvider::class,
            BladeIconsServiceProvider::class,
            FilamentServiceProvider::class,
            FormsServiceProvider::class,
            InfolistsServiceProvider::class,
            LivewireServiceProvider::class,
            NotificationsServiceProvider::class,
            //SpatieLaravelSettingsPluginServiceProvider::class,
            //SpatieLaravelTranslatablePluginServiceProvider::class,
            SupportServiceProvider::class,
            TablesServiceProvider::class,
            WidgetsServiceProvider::class,

            FilamentCMSServiceProvider::class,
            \Tests\ResourceServiceProvider::class,
//            PartPanelProvider::class
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
            \Tests\Fixtures\App\Models\Page::class,
            \Tests\Fixtures\App\Models\Post::class,
            PageWithRootPrefix::class,
            PageWithArgs::class
        ]);

        $app['config']->set('filament-cms.compile', true);

        $app['config']->set('view.paths', array_merge(
            config('view.paths'),
            [
                __DIR__.'/Fixtures/resources/views'
            ],
        ));

//        $app['config']->set('filament.livewire.path', realpath(__DIR__.'/Fixtures/app/Filament'));
        $app['config']->set('filament.resources.namespace', 'Tests\Fixtures\App\Filament\Resources');
//        $app['config']->set('filament.resources.path', realpath(__DIR__.'/Fixtures/app/Filament/Resources'));
//        $app['config']->set('filament.resources.register', [
//            \Tests\Fixtures\App\Filament\Resources\PageResource::class
//        ]);
    }


}
