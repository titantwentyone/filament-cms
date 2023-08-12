<?php

use Titantwentyone\FilamentCMS\Filament\Resources\PartResource\Pages\CreatePart;

it('has the correct fields', function() {
   \Pest\Livewire\livewire(CreatePart::class)
        ->assertFormFieldExists('slug')
        ->assertFormFieldExists('location');
});

it('will slugify the slug', function () {
    \Pest\Livewire\livewire(CreatePart::class)
        ->fillForm([
            'slug' => 'Not a SLUG'
        ])
        ->assertFormSet([
            'slug' => 'not-a-slug'
        ]);
});

it('will provide custom fields', function () {

    config()->set('filament-cms.part_fields', [
        \Filament\Forms\Components\Textarea::make('address')
    ]);

    \Pest\Livewire\livewire(CreatePart::class)
        ->assertFormFieldExists('content.address');

});

it('will provide custom fields via a closure', function () {
    config()->set('filament-cms.part_fields', fn($location) => match($location) {
        'header' => [
            \Filament\Forms\Components\TextInput::make('header_field')
        ],
        'footer' => [
            \Filament\Forms\Components\TextInput::make('footer_field')
        ]
    });

    //@todo PR to provide assertFormFieldDoesNotExist
    \Pest\Livewire\livewire(CreatePart::class)
        ->fillForm([
            'location' => 'header'
        ])
        ->assertFormFieldExists('content.header_field')
        ->fillForm([
            'location' => 'footer'
        ])
        ->assertFormFieldExists('content.footer_field');
});