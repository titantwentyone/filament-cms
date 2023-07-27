<?php

namespace Tests\Skeleton\app\Filament\Resources\PageResource\Pages;

use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use Tests\Skeleton\app\Filament\Resources\PageResource;
use Titantwentyone\FilamentCMS\Filament\Resources\Concerns\RendersView;

class EditPage extends EditRecord
{
    protected static string $resource = PageResource::class;

    use RendersView;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}