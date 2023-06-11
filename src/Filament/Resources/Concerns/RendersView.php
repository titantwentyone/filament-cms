<?php

namespace Titantwentyone\FilamentCMS\Filament\Resources\Concerns;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;

trait RendersView
{
    protected function renderView(string $field)
    {
        $content = Blade::render($this->record->{$field});

        $disk = Storage::build([
            'driver' => 'local',
            'root' => storage_path('/cms')
        ]);

        $disk->put($this->record->slug.'.blade.php', $content);

        $this->runViteBuild();
    }

    protected function runViteBuild()
    {
        $process = new Process(['npm', 'run', 'build']);
        $process->run();
    }
}