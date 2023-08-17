<?php

it('has a create action', function() {

    \Pest\Livewire\livewire(\Titantwentyone\FilamentCMS\Filament\Resources\PartResource\Pages\ListParts::class)
        ->assertPageActionExists('create');
})
->covers(\Titantwentyone\FilamentCMS\Filament\Resources\PartResource\Pages\ListParts::class);