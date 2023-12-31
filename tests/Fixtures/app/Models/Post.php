<?php

namespace Tests\Fixtures\App\Models;

use Filament\Forms\Components\RichEditor;
use Illuminate\Database\Eloquent\SoftDeletes;
use PHPUnit\Framework\Attributes\CodeCoverageIgnore;
use Titantwentyone\FilamentCMS\Contracts\Content;

#[CodeCoverageIgnore]
class Post extends Content
{
    use SoftDeletes;

    public static $prefix = '/blog';
    public static $view = 'post';

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