<?php

namespace Tests\Fixtures\App\Filament\Resources;

use Tests\Fixtures\App\Filament\Resources\PageResource\Pages;
use Tests\Fixtures\App\Models\Page;
use Filament\Forms;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Resource;
use Filament\Tables;
use Illuminate\Support\Str;
use Titantwentyone\FilamentCMS\Filament\Contracts\ContentResource;

class PageResource extends ContentResource
{
    protected static ?string $model = Page::class;

    public static string $contentField = 'content';
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPages::route('/'),
            'create' => Pages\CreatePage::route('/create'),
            'edit' => Pages\EditPage::route('/{record}/edit'),
        ];
    }    
}