<?php

namespace Tests\Fixtures\App\Domain;

use Filament\Forms\Components\TextInput;

class PartManager extends \Titantwentyone\FilamentCMS\Domain\Part\Contracts\Manager
{
    protected array $views = [
        'header' => 'cms.parts.header'
    ];

    public function fields($location): array
    {
        return [
            TextInput::make('test_part_field')
        ];
    }
}