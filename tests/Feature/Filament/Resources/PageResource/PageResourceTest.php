<?php

it('will generate a slug for content if the content is being created', function () {

    $page = \Tests\Fixtures\App\Models\Page::make([
        'title' => 'Testing',
        'slug' => 'testing',
        'is_published' => true
    ]);

    \Pest\Livewire\livewire(\Tests\Fixtures\App\Filament\Resources\PageResource\Pages\CreatePage::class)
        ->fillForm($page->attributesToArray())
        ->set('data.title', 'A Different Title')
        ->assertSet('data.slug', 'a-different-title');
})
->covers(\Tests\Fixtures\App\Filament\Resources\PageResource::class);

it('will show a table of content', function () {

    $page = \Tests\Fixtures\App\Models\Page::create([
        'title' => 'Testing',
        'slug' => 'testing',
        'is_published' => true
    ]);

    \Pest\Livewire\livewire(\Tests\Fixtures\App\Filament\Resources\PageResource\Pages\ListPages::class)
        ->assertCanSeeTableRecords(\Tests\Fixtures\App\Models\Page::all())
        ->assertTableColumnExists('title')
        ->assertTableColumnStateSet('title', 'Testing', $page->id);

})
->covers(\Tests\Fixtures\App\Filament\Resources\PageResource::class);