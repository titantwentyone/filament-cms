<?php

it('will route to the correct view', function() {

    \Tests\Skeleton\app\Models\Page::create([
        'title' => 'Testing',
        'slug' => 'testing',
        'is_published' => true
    ]);

    \Tests\Skeleton\app\Models\Post::create([
        'title' => 'A blog post',
        'slug' => 'first',
        'is_published' => true
    ]);

    $this->get('/testing')
        ->assertSuccessful()
        ->assertViewIs('page');

    $this->get('/first')
        ->assertNotFound();

    $this->get('/blog/first')
        ->assertSuccessful()
        ->assertViewIs('post');

});