<?php

it('has a create action', function() {

    \Pest\Livewire\livewire(\Tests\Fixtures\App\Filament\Resources\PageResource\Pages\ListPages::class)
        ->assertPageActionExists('create');
})
->covers(\Tests\Fixtures\App\Filament\Resources\PageResource\Pages\ListPages::class);