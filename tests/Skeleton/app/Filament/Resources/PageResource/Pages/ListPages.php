<?php

namespace Tests\Skeleton\app\Filament\Resources\PageResource\Pages;

use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Tests\Skeleton\app\Filament\Resources\PageResource;

class ListPages extends ListRecords
{
    protected static string $resource = PageResource::class;

    protected function getActions(): array
{
    return [
        Actions\CreateAction::make(),
    ];
}
}