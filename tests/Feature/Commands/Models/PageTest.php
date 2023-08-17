<?php

it('will add a content models form fields to the resource', function() {

    \Pest\Livewire\livewire(\Tests\Fixtures\App\Filament\Resources\PageResource\Pages\CreatePage::class)
        ->assertFormFieldExists('content');
})
->covers(\Tests\Fixtures\App\Models\Page::class);