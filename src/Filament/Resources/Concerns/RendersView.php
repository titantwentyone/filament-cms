<?php

namespace Titantwentyone\FilamentCMS\Filament\Resources\Concerns;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;
use Titantwentyone\FilamentCMS\Domain\Process\AssetCompilationProcess;
use Titantwentyone\FilamentCMS\Models\Part;

trait RendersView
{
    private $cms_disk;
    public function initializeRendersView()
    {
        $this->cms_disk = app('filament_cms_render');
    }

    protected function renderView(string $field)
    {
//        $disk = Storage::build([
//            'driver' => 'local',
//            'root' => storage_path('/cms')
//        ]);

        if(get_class($this->record) == Part::class) {
            $content = $this->record->render();
            $this->cms_disk->put('parts/'.$this->record->slug.'.blade.php', $content);
        } else {
            $content = Blade::render($this->record->{$field});
            $this->cms_disk->put($this->record->slug.'.blade.php', $content);
        }

        $render_info = [
            'type' => get_class($this->record),
            'id' => $this->record->id
        ];

        $render_info = collect($render_info)->join('\n');

        Log::channel('filament_cms_dynamic_render')->info($render_info);

        $this->runViteBuild();
    }

    protected function runViteBuild()
    {
        //$process = new Process(['npm', 'run', 'build']);
        $process = app(AssetCompilationProcess::class)->get();
        $process->setWorkingDirectory(base_path());
        $process->run(function($type, $buffer) {
            Log::channel('filament_cms_dynamic_render')->info($buffer);
        });
    }

    protected function afterSave()
    {
        $this->renderView(self::$resource::$contentField);
    }

    protected function afterCreate(): void
    {
        $this->renderView(self::$resource::$contentField);
    }
}