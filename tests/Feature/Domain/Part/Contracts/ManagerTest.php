<?php

use Filament\Forms\Components\TextInput;

it('will provide views', function() {

    $manager = new \Tests\Fixtures\App\Domain\PartManager();

    expect($manager->views())->toEqual([
        'header' => 'cms.parts.header'
    ]);
})
->covers(\Titantwentyone\FilamentCMS\Domain\Part\Contracts\Manager::class);

it('will provide fields', function () {

    $manager = new \Tests\Fixtures\App\Domain\PartManager();

    expect($manager->fields('header'))->toEqual([
        TextInput::make('test_part_field')
    ]);
})
->covers(\Titantwentyone\FilamentCMS\Domain\Part\Contracts\Manager::class);