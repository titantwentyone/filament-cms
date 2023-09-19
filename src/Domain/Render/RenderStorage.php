<?php

namespace Titantwentyone\FilamentCMS\Domain\Render;

use Illuminate\Support\Facades\Storage;

class RenderStorage
{
    public function getStorage()
    {
        return Storage::build([
            'driver' => 'local',
            'root' => config('filament-cms.dynamic_render_location') ?? base_path('/storage/cms'),
            'throw' => true
        ]);
    }
}