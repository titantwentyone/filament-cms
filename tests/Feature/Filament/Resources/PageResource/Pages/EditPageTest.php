<?php

it('has a delete action', function() {

    $page = Tests\Fixtures\App\Models\Page::create([
        'title' => 'Testing',
        'slug' => 'testing',
        'is_published' => true
    ]);

    \Pest\Livewire\livewire(\Tests\Fixtures\App\Filament\Resources\PageResource\Pages\EditPage::class, [
        'record' => $page->id
    ])
    ->assertActionExists('delete');

})
->covers(\Tests\Fixtures\App\Filament\Resources\PageResource\Pages\EditPage::class);