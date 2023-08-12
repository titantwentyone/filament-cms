<?php

namespace Titantwentyone\FilamentCMS\Filament\Resources\PartResource\Pages;

use Filament\Pages\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Titantwentyone\FilamentCMS\Filament\Resources\Concerns\RendersView;
use Titantwentyone\FilamentCMS\Filament\Resources\PartResource;

class EditPart extends EditRecord
{
    use RendersView;

    protected static string $resource = PartResource::class;

    protected function getActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
