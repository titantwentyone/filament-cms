<?php

it('has the correct fields', function() {

    \Pest\Livewire\livewire(\Tests\Fixtures\App\Filament\Resources\PageResource\Pages\CreatePage::class)
        ->assertFormFieldExists('title');
})
->covers(\Tests\Fixtures\App\Filament\Resources\PageResource::class);