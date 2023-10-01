<?php

namespace Titantwentyone\FilamentCMS\Filament\Resources;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\CodeCoverageIgnore;
use Titantwentyone\FilamentCMS\Filament\Resources\Concerns\RendersView;
use Titantwentyone\FilamentCMS\Filament\Resources\PartResource\Pages;
use Titantwentyone\FilamentCMS\Models\Part;

class PartResource extends Resource
{
    protected static ?string $model = Part::class;

    protected static ?string $slug = 'parts';

    protected static ?string $navigationGroup = 'Content';

    protected static ?string $recordTitleAttribute = 'id';

    public static string $contentField = 'content';

    protected static ?int $navigationSort = 999;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('slug')
                    ->afterStateUpdated(fn($component, $state) => $component->state(Str::of($state)->lower()->slug())),
                Select::make('location')
                    ->options(config('filament-cms.part_locations'))
                    ->reactive(),
                Section::make('fields')
                    ->reactive()
                    ->statePath('content')
                    ->schema(function($get) {
                        $fields = config('filament-cms.part_fields');
                        if(is_callable($fields)) {
                            if($get('location')) {
                                return $fields($get('location'));
                            } else {
                                return [];
                            }
                        } else {
                            return $fields;
                        }
})
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('slug')
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListParts::route('/'),
            'create' => Pages\CreatePart::route('/create'),
            'edit' => Pages\EditPart::route('/{record}/edit'),
        ];
    }

    #[CodeCoverageIgnore]
    public static function getGloballySearchableAttributes(): array
    {
        return [];
    }
}
