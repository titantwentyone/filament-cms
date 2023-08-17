<?php

it('will render a content part', function() {

    app()->bind(\Titantwentyone\FilamentCMS\Domain\Part\Contracts\Manager::class, \Tests\Fixtures\App\Domain\PartManager::class);

    \Titantwentyone\FilamentCMS\Models\Part::create([
        'slug' => 'banner',
        'location' => 'header',
        'content' => [
            'test_part_field' => 'Testing'
        ]
    ]);

    ob_start();
    content_part('header');
    $result = ob_end_flush();

    expect($result)->toEqual('Testing');
})
->coversFunction('content_part');