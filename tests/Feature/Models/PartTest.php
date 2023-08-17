<?php

it('will render a part when part_views is given as an array', function() {

    $part = \Titantwentyone\FilamentCMS\Models\Part::create([
        'slug' => 'banner',
        'location' => 'header',
        'content' => [
            'test_part_field' => 'this is text in a banner'
        ]
    ]);

    app()->bind(\Titantwentyone\FilamentCMS\Domain\Part\Contracts\Manager::class, \Tests\Fixtures\App\Domain\PartManager::class);

    $response = $part->render();

    expect($response)->toEqual('this is text in a banner');
})
->covers(\Titantwentyone\FilamentCMS\Models\Part::class);