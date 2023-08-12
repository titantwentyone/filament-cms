<?php

namespace Tests\Fixtures\App\Filament\Resources\PageResource\Pages;

use Tests\Fixtures\App\Filament\Resources\PageResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use Titantwentyone\FilamentCMS\Filament\Resources\Concerns\RendersView;

class EditPage extends EditRecord
{
    use RendersView;

    protected static string $resource = PageResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}