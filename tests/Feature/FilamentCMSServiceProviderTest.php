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

it('will normalize an array', function () {

    $arr = [
        'just a value',
        'key' => 'value'
    ];

    $arr = \Illuminate\Support\Arr::normalize($arr);

    expect($arr)->toEqual([
        'just a value' => 'just a value',
        'key' => 'value'
    ]);

})
->covers(\Titantwentyone\FilamentCMS\FilamentCMSServiceProvider::class);

it('will provide a default asset compilation process', function () {

    $process = null;
    app()->bind(\Symfony\Component\Process\Process::class, function($app) use (&$process) {
        $process = new Symfony\Component\Process\Process(['test_command']);
        return $process;
    });

    $asset_compilation_process = app(\Titantwentyone\FilamentCMS\Domain\Process\AssetCompilationProcess::class);

    expect($asset_compilation_process->get())->toBe($process);
})
->covers(\Titantwentyone\FilamentCMS\FilamentCMSServiceProvider::class);