<?php

it('will set and return a process', function() {

    $process = new Symfony\Component\Process\Process(['some_command']);

    $asset_compilation_process = new \Titantwentyone\FilamentCMS\Domain\Process\AssetCompilationProcess($process);

    expect($asset_compilation_process->get())->toBe($process);
})
->covers(\Titantwentyone\FilamentCMS\Domain\Process\AssetCompilationProcess::class);