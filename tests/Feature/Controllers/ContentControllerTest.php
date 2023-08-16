<?php

it('will route to the correct view', function() {

    Tests\Fixtures\App\Models\Page::create([
        'title' => 'Testing',
        'slug' => 'testing',
        'is_published' => true
    ]);

    Tests\Fixtures\App\Models\Post::create([
        'title' => 'A blog post',
        'slug' => 'first',
        'is_published' => true
    ]);

    $this->get('/pages/testing')
        ->assertSuccessful()
        ->assertViewIs('page');

    $this->get('/first')
        ->assertNotFound();

    $this->get('/blog/first')
        ->assertSuccessful()
        ->assertViewIs('post');

})
->covers(\Titantwentyone\FilamentCMS\Controllers\ContentController::class);

it('will route to a root view', function () {

});