<?php

namespace Tests\Fixtures\App\Models;

use Filament\Forms\Components\RichEditor;
use Illuminate\Database\Eloquent\SoftDeletes;
use Titantwentyone\FilamentCMS\Contracts\Content;

class PageWithArgs extends Content
{
    protected $table = 'pages';

    use SoftDeletes;

    public static $prefix = '/pages_with_args';
    public static $view = 'page';

    protected $fillable = [
        'title',
        'slug',
        'content',
        'created_at',
        'is_published',
        'is_root',
        'updated_at',
        'uses_route_parameters'
    ];

    public static function form(): array
    {
        return [
            RichEditor::make('content')
        ];
    }

    public static function hasRouteArguments(): array
    {
        return [
            'page' => [
                'page'
            ],
            'foo' => [
                'bar'
            ]
        ];
    }
}