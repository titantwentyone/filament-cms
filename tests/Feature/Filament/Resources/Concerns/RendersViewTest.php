<?php

it('will create temp files for content in storage for css parsing', function() {

    $fake_disk = \Illuminate\Support\Facades\Storage::fake('filament_cms_render');

//    app()->bind('filament_cms_render', function() use ($fake_disk) {
//        return $fake_disk;
//    });

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

});


it('will create temp files for parts in storage for css parsing', function () {

    \Illuminate\Support\Facades\Config::set('filament-cms.part_locations', [
         'header'
    ]);

    \Illuminate\Support\Facades\Config::set('filament-cms.part_fields', [
        'header' => \Filament\Forms\Components\TextInput::make('header_text')
    ]);

    \Illuminate\Support\Facades\Config::set('filament-cms.part_views', [
        'header' => 'cms.parts.header'
    ]);

    $fake_disk = \Illuminate\Support\Facades\Storage::fake('filament_cms_render');

//    app()->bind('filament_cms_render', function () use ($fake_disk) {
//        return $fake_disk;
//    });

    $part = \Titantwentyone\FilamentCMS\Models\Part::make([
        'location' => 'header',
        'slug' => 'test-part',
        'content' => [
            'header_text' => 'Testing'
        ]
    ]);

    \Pest\Livewire\livewire(\Titantwentyone\FilamentCMS\Filament\Resources\PartResource\Pages\CreatePart::class)
        ->fillForm($part->attributesToArray())
        ->call('create');

    expect(\Titantwentyone\FilamentCMS\Models\Part::count())->toBe(1);

    expect($fake_disk->exists('/parts/test-part.blade.php'))->toBeTrue();
    expect($fake_disk->get('/parts/test-part.blade.php'))->toBe('Testing');

});

test('user can change location of rendered parts and content', function () {

    app()->config['filament-cms.dynamic_render_location'] = base_path('/storage/different-cms-render-location');

    expect(app('filament_cms_render')->path(''))->toBe(base_path('/storage/different-cms-render-location/'));
});