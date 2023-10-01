<?php

it('has a delete action', function() {

    $part = \Titantwentyone\FilamentCMS\Models\Part::create([
        'title' => 'Testing',
        'slug' => 'testing',
        'is_published' => true
    ]);

    \Pest\Livewire\livewire(\Titantwentyone\FilamentCMS\Filament\Resources\PartResource\Pages\EditPart::class, [
        'record' => $part->id
    ])
        ->assertActionExists('delete');

})
->covers(\Titantwentyone\FilamentCMS\Filament\Resources\PartResource\Pages\EditPart::class);