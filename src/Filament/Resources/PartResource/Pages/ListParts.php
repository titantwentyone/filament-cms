<?php

namespace Titantwentyone\FilamentCMS\Filament\Resources\PartResource\Pages;

use Filament\Pages\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Titantwentyone\FilamentCMS\Filament\Resources\PartResource;

class ListParts extends ListRecords
{
    protected static string $resource = PartResource::class;

    protected function getActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
