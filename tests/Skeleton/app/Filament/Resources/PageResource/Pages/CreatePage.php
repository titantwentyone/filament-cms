<?php

namespace Tests\Skeleton\app\Filament\Resources\PageResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Tests\Skeleton\app\Filament\Resources\PageResource;
use Titantwentyone\FilamentCMS\Filament\Resources\Concerns\RendersView;

class CreatePage extends CreateRecord
{
    use RendersView;

    protected static string $resource = PageResource::class;

    protected function afterCreate(): void
    {
        $this->renderView(self::$resource::$contentField);
    }
}