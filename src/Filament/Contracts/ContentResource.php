<?php

namespace Titantwentyone\FilamentCMS\Filament\Contracts;

use Filament\Forms;
use Filament\Forms\Components;
use Filament\Resources\Pages\PageRegistration;
use Filament\Support\Enums\Alignment;
use Filament\Tables;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Resource;
use Illuminate\Support\Str;
use Tests\Fixtures\App\Filament\Resources\PageResource;
use Tests\Fixtures\App\Models\Page;

abstract class ContentResource extends Resource
{
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Content';
    public static function form(Forms\Form $form): Forms\Form
    {
        $model = static::$model;

        return $form
            ->schema([
                Components\Grid::make()
                    ->columns(1)
                    ->schema(static::headerForm()),
                Components\Grid::make()
                    ->columns(1)
                    ->schema($model::form()),
                Forms\Components\Grid::make()
                    ->columns(1)
                    ->schema(static::footerForm())
            ]);
    }
    private static function headerForm(): array
    {
        return [
            Components\TextInput::make('title')
                ->required()
                ->reactive()
                ->afterStateUpdated(function($state, $set, $livewire) {
                    if($livewire instanceof CreateRecord) {
                        $set('slug', Str::slug($state));
                    }
                }),
            Components\Toggle::make('is_root'),
            Components\TextInput::make('slug')
                ->required()
                ->unique(ignoreRecord: true)
        ];
    }

    private static function footerForm(): array
    {
        return [
            Components\DatePicker::make('created_at')
                ->required()
                ->default(fn() => now()),
            Components\Toggle::make('is_published')
                ->default(false)
        ];
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->recordUrl(false)
            ->columns([
                Tables\Columns\Layout\Split::make([
                    Tables\Columns\TextColumn::make('title')
                        ->searchable()
                        ->sortable()
                        ->url(function($record) {
                            return static::getUrl('edit', ['record' => $record]);
                        }),
                    Tables\Columns\Layout\Stack::make([
                        Tables\Columns\Layout\Split::make([
                            Tables\Columns\ToggleColumn::make('is_root')
                                ->onColor('success')
                                ->onIcon('heroicon-o-home')
                                ->offIcon('heroicon-o-home'),
                            Tables\Columns\ToggleColumn::make('is_published')
                                ->onColor('success')
                                ->onIcon('heroicon-o-bolt')
                                ->offIcon('heroicon-o-bolt')

                        ])->grow(false)
                    ])->alignment(Alignment::End)
                ])
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
}