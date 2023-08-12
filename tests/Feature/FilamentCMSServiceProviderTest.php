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

    $this->get('/testing')
        ->assertSuccessful()
        ->assertViewIs('page');

    $this->get('/first')
        ->assertNotFound();

    $this->get('/blog/first')
        ->assertSuccessful()
        ->assertViewIs('post');

});

it('will set up the relevant disks', function () {

    \Illuminate\Support\Facades\Storage::spy();

    $handler = app(\Titantwentyone\FilamentCMS\Commands\Composites\StubHandler::class);

    \Illuminate\Support\Facades\Storage::shouldHaveReceived('build')
        ->with([
            'driver' => 'local',
            'root' => app_path('/Models')
        ]);

    \Illuminate\Support\Facades\Storage::shouldHaveReceived('build')
        ->with([
            'driver' => 'local',
            'root' => database_path('migrations')
        ]);

    \Illuminate\Support\Facades\Storage::shouldHaveReceived('build')
        ->with([
            'driver' => 'local',
            'root' => base_path(config('filament.livewire.path'))
        ]);

});