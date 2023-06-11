<?php

namespace Tests\Skeleton\app\Models;

use Filament\Forms\Components\RichEditor;
use Illuminate\Database\Eloquent\SoftDeletes;
use Titantwentyone\FilamentCMS\Contracts\Content;

class Post extends Content
{
    use SoftDeletes;

    public static $prefix = "/blog";
    public static $view = 'post';

    protected $fillable = [
        'title',
        'slug',
        'content',
        'created_at',
        'is_published',
        'is_root'
    ];

    public static function form(): array
    {
        return [
            RichEditor::make('content')
        ];
    }
}
