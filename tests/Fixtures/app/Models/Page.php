<?php

namespace Tests\Fixtures\App\Models;

use Filament\Forms\Components\RichEditor;
use Illuminate\Database\Eloquent\SoftDeletes;
use Titantwentyone\FilamentCMS\Contracts\Content;

class Page extends Content
{
    use SoftDeletes;

    public static $prefix = '/pages';
    public static $view = 'page';

    protected $fillable = [
        'title',
        'slug',
        'content',
        'created_at',
        'is_published',
        'is_root',
        'updated_at'
    ];

    public static function form(): array
    {
        return [
            RichEditor::make('content')
        ];
    }
}