<?php

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
            'root' => base_path(config('filament.resources.path'))
        ]);

    \Illuminate\Support\Facades\Storage::shouldHaveReceived('build')
        ->with([
            'driver' => 'local',
            'root' => realpath(__DIR__.'/../../stubs')
        ]);

})
->covers(\Titantwentyone\FilamentCMS\FilamentCMSServiceProvider::class);