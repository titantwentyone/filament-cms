<?php

use Symfony\Component\Process\Process;
use Titantwentyone\FilamentCMS\Domain\Process\AssetCompilationProcess;

beforeEach(function() {
    \TiMacDonald\Log\LogFake::bind();

    //mock the symfony process
    app()->bind(AssetCompilationProcess::class, function($app) {
        $command = 'ls -la';
        $command = explode(" ", $command);
        $process = app()->makeWith(Process::class, ['command' => $command]);
        return new AssetCompilationProcess($process);
    });
});

it('will create temp files for content in storage for css parsing', function() {

    app()->bind(\Titantwentyone\FilamentCMS\Domain\Render\RenderStorage::class, function() use (&$fake_disk) {
        $render_storage = new class {
            public function getStorage()
            {
                return \Illuminate\Support\Facades\Storage::fake();
            }
        };
        return $render_storage;
    });

    $fake_disk = app(\Titantwentyone\FilamentCMS\Domain\Render\RenderStorage::class)->getStorage();

    $page = Tests\Fixtures\App\Models\Page::make([
        'title' => 'Test Page',
        'slug' => 'test-page',
        'is_published' => true,
        'content' => 'hello'
    ]);

    \Pest\Livewire\livewire(Tests\Fixtures\App\Filament\Resources\PageResource\Pages\CreatePage::class)
        ->fillForm($page->attributesToArray())
        ->call('create');

    expect(Tests\Fixtures\App\Models\Page::count())->toBe(1);

    expect($fake_disk->exists('/test-page.blade.php'))->toBeTrue();
    expect($fake_disk->get('/test-page.blade.php'))->toBe('hello');

})
->covers(\Titantwentyone\FilamentCMS\Filament\Resources\Concerns\RendersView::class);


it('will create temp files for parts in storage for css parsing', function () {

    app()->bind(\Titantwentyone\FilamentCMS\Domain\Part\Contracts\Manager::class, \Tests\Fixtures\App\Domain\PartManager::class);

    \Illuminate\Support\Facades\Config::set('filament-cms.part_locations', [
         'header'
    ]);

    \Illuminate\Support\Facades\Config::set('filament-cms.part_fields', [
        'header' => \Filament\Forms\Components\TextInput::make('header_text')
    ]);

    \Illuminate\Support\Facades\Config::set('filament-cms.part_views', [
        'header' => 'cms.parts.header'
    ]);

    app()->bind(\Titantwentyone\FilamentCMS\Domain\Render\RenderStorage::class, function() use (&$fake_disk) {
        $render_storage = new class {
            public function getStorage()
            {
                return \Illuminate\Support\Facades\Storage::fake();
            }
        };
        return $render_storage;
    });

    $fake_disk = app(\Titantwentyone\FilamentCMS\Domain\Render\RenderStorage::class)->getStorage();

    $part = \Titantwentyone\FilamentCMS\Models\Part::make([
        'location' => 'header',
        'slug' => 'test-part',
        'content' => [
            'test_part_field' => 'Testing'
        ]
    ]);

    \Pest\Livewire\livewire(\Titantwentyone\FilamentCMS\Filament\Resources\PartResource\Pages\CreatePart::class)
        ->fillForm($part->attributesToArray())
        ->call('create');

    expect(\Titantwentyone\FilamentCMS\Models\Part::count())->toBe(1);

    expect($fake_disk->exists('/parts/test-part.blade.php'))->toBeTrue();
    expect($fake_disk->get('/parts/test-part.blade.php'))->toBe('Testing');

})
->covers(\Titantwentyone\FilamentCMS\Filament\Resources\Concerns\RendersView::class);

test('user can change location of rendered parts and content', function () {

    app()->config['filament-cms.dynamic_render_location'] = base_path('/storage/different-cms-render-location');

    expect(app(\Titantwentyone\FilamentCMS\Domain\Render\RenderStorage::class)->getStorage()->path(''))
        ->toBe(base_path('/storage/different-cms-render-location/'));
})
->covers(\Titantwentyone\FilamentCMS\Filament\Resources\Concerns\RendersView::class);

it('will run a command when saving content', function () {

    app()->bind(\Titantwentyone\FilamentCMS\Domain\Process\AssetCompilationProcess::class, function() {
        $process = new Symfony\Component\Process\Process(['echo', '"hello"']);
        class ps {

            private $process;
            public function __construct(\Symfony\Component\Process\Process $process) {
                return $this->process = $process;
            }

            public function get()
            {
                return $this->process;
            }
        };

        return new ps($process);
    });

    $fake_disk = \Illuminate\Support\Facades\Storage::fake('filament_cms_render');

    $page = Tests\Fixtures\App\Models\Page::make([
        'title' => 'Test Page',
        'slug' => 'test-page',
        'is_published' => true,
        'content' => 'hello'
    ]);

    \Pest\Livewire\livewire(Tests\Fixtures\App\Filament\Resources\PageResource\Pages\CreatePage::class)
        ->fillForm($page->attributesToArray())
        ->call('create');

    $page_id = \Tests\Fixtures\App\Models\Page::first()->id;

    \Illuminate\Support\Facades\Log::channel('filament_cms_dynamic_render')
        ->assertLoggedTimes(function(\TiMacDonald\Log\LogEntry $log) use ($page_id) {
            return $log->level == 'info' && $log->message == \Tests\Fixtures\App\Models\Page::class."\\n{$page_id}";
        }, 1);

    \Illuminate\Support\Facades\Log::channel('filament_cms_dynamic_render')
        ->assertLoggedTimes(function(\TiMacDonald\Log\LogEntry $log) {
            return $log->level == 'info' && $log->message == "\"hello\"\n";
        }, 1);
})
->covers(\Titantwentyone\FilamentCMS\Filament\Resources\Concerns\RendersView::class);

it('will run a command when updating content', function () {

    app()->bind(\Titantwentyone\FilamentCMS\Domain\Process\AssetCompilationProcess::class, function() {
        $process = new Symfony\Component\Process\Process(['echo', '"hello"']);
        class pu {

            private $process;
            public function __construct(\Symfony\Component\Process\Process $process) {
                return $this->process = $process;
            }

            public function get()
            {
                return $this->process;
            }
        };

        return new pu($process);
    });

    $fake_disk = \Illuminate\Support\Facades\Storage::fake('filament_cms_render');

    $page = Tests\Fixtures\App\Models\Page::create([
        'title' => 'Test Page',
        'slug' => 'test-page',
        'is_published' => true,
        'content' => 'hello'
    ]);

    \Pest\Livewire\livewire(Tests\Fixtures\App\Filament\Resources\PageResource\Pages\EditPage::class, [
            'record' => $page->id
        ])
        ->fillForm($page->attributesToArray())
        ->call('save');

    $page_id = \Tests\Fixtures\App\Models\Page::first()->id;

    \Illuminate\Support\Facades\Log::channel('filament_cms_dynamic_render')
        ->assertLoggedTimes(function(\TiMacDonald\Log\LogEntry $log) use ($page_id) {
            return $log->level == 'info' && $log->message == \Tests\Fixtures\App\Models\Page::class."\\n{$page_id}";
        }, 1);

    \Illuminate\Support\Facades\Log::channel('filament_cms_dynamic_render')
        ->assertLoggedTimes(function(\TiMacDonald\Log\LogEntry $log) {
            return $log->level == 'info' && $log->message == "\"hello\"\n";
        }, 1);
})
    ->covers(\Titantwentyone\FilamentCMS\Filament\Resources\Concerns\RendersView::class);