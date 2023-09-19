<?php

namespace Titantwentyone\FilamentCMS\Filament\Resources\Concerns;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\UnableToWriteFile;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use Titantwentyone\FilamentCMS\Domain\Process\AssetCompilationProcess;
use Titantwentyone\FilamentCMS\Domain\Render\RenderStorage;
use Titantwentyone\FilamentCMS\Models\Part;

trait RendersView
{
    private $cms_disk;
    public function bootRendersView(Filesystem $cms_disk)
    {
        $render_storage = app(RenderStorage::class);
        $this->cms_disk = $render_storage->getStorage();
    }

    protected function renderView(string $field)
    {
//        $disk = Storage::build([
//            'driver' => 'local',
//            'root' => storage_path('/cms')
//        ]);

        if(config('filament-cms.compile') == true) {
            try {
                if(get_class($this->record) == Part::class) {
                    $content = $this->record->render();
                    $this->cms_disk->put('parts/'.$this->record->slug.'.blade.php', $content);
                } else {
                    if($this->record->{$field}) {
                        $content = Blade::render($this->record->{$field});
                        $result = $this->cms_disk->put($this->record->slug.'.blade.php', $content);
                    }
                }
            } catch(\Exception $e) {
                dd($e);
            }

            $render_info = [
                'type' => get_class($this->record),
                'id' => $this->record->id
            ];

            $render_info = collect($render_info)->join('\n');

            Log::channel('filament_cms_dynamic_render')->info($render_info);

            $this->runViteBuild();
        }
    }

    protected function runViteBuild()
    {
        //$process = new Process(['npm', 'run', 'build']);
        $process = app(AssetCompilationProcess::class)->get();
        $process->setWorkingDirectory(base_path());

        try {
            $process->mustRun(function($type, $buffer) {
                Log::channel('filament_cms_dynamic_render')->info($buffer);
            });
        } catch(ProcessFailedException $e) {
            dd($e);
        }

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
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