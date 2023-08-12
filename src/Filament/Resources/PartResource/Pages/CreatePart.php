<?php

namespace Titantwentyone\FilamentCMS\Filament\Resources\PartResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Titantwentyone\FilamentCMS\Filament\Resources\Concerns\RendersView;
use Titantwentyone\FilamentCMS\Filament\Resources\PartResource;

class CreatePart extends CreateRecord
{
    use RendersView;

    protected static string $resource = PartResource::class;
}
