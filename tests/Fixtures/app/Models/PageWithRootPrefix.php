<?php

namespace Tests\Fixtures\App\Models;

use Filament\Forms\Components\RichEditor;
use Illuminate\Database\Eloquent\SoftDeletes;
use PHPUnit\Framework\Attributes\CodeCoverageIgnore;
use Titantwentyone\FilamentCMS\Contracts\Content;

#[CodeCoverageIgnore]
class PageWithRootPrefix extends Content
{
    protected $table = 'pages';

    use SoftDeletes;

    public static $prefix = '/';
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