<?php

namespace Titantwentyone\FilamentCMS\Filament\Resources\Concerns;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;
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

        $this->runViteBuild();
    }

    protected function runViteBuild()
    {
        $process = new Process(['npm', 'run', 'build']);
        $process->setWorkingDirectory(base_path());
        $process->run();}

    protected function afterSave()
    {
        $this->renderView(self::$resource::$contentField);
    }

    protected function afterCreate(): void
    {
        $this->renderView(self::$resource::$contentField);
    }
}