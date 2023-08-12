<?php

namespace Tests\Fixtures\App\Filament\Resources\PageResource\Pages;

use Tests\Fixtures\App\Filament\Resources\PageResource;
use Filament\Resources\Pages\CreateRecord;
use Titantwentyone\FilamentCMS\Filament\Resources\Concerns\RendersView;

class CreatePage extends CreateRecord
{
    use RendersView;

    protected static string $resource = PageResource::class;
}