<?php

it('will return storage', function() {

    $render_storage = new \Titantwentyone\FilamentCMS\Domain\Render\RenderStorage();

    expect(realpath($render_storage->getStorage()->path('')))->toBe(realpath(base_path('/storage/cms')));
})
->covers(\Titantwentyone\FilamentCMS\Domain\Render\RenderStorage::class);
