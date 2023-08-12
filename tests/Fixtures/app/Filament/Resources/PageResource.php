<?php

namespace Tests\Fixtures\App\Filament\Resources;

use Tests\Fixtures\App\Filament\Resources\PageResource\Pages;
use Tests\Fixtures\App\Models\Page;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Support\Str;

class PageResource extends Resource
{
    protected static ?string $model = Page::class;
    protected static ?string $navigationIcon = 'heroicon-o-collection';
    protected static ?string $navigationGroup = 'content';
    public static string $contentField = 'content';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make()
                    ->columns(1)
                    ->schema(static::headerForm()),
                Forms\Components\Grid::make()
                    ->columns(1)
                    ->schema(Page::form()),
                Forms\Components\Grid::make()
                    ->columns(1)
                    ->schema(static::footerForm())
            ]);
    }

    private static function headerForm(): array
    {
        return [
            Forms\Components\TextInput::make('title')
                ->required()
                ->reactive()
                ->afterStateUpdated(function($state, $set, $livewire) {
                    if($livewire instanceof CreateRecord) {
                        $set('slug', Str::slug($state));
                    }
                }),
            Forms\Components\Toggle::make('is_root'),
            Forms\Components\TextInput::make('slug')
                ->required()
                ->unique(ignoreRecord: true)
        ];
    }

    private static function footerForm(): array
    {
        return [
            Forms\Components\DatePicker::make('created_at')
                ->required()
                ->default(fn() => now()),
            Forms\Components\Toggle::make('is_published')
                ->default(false)
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
    
    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPages::route('/'),
            'create' => Pages\CreatePage::route('/create'),
            'edit' => Pages\EditPage::route('/{record}/edit'),
        ];
    }    
}