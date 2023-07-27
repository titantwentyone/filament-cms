<?php

namespace Titantwentyone\FilamentCMS\Models;

use Filament\Tables\Concerns\HasContent;
use Illuminate\Database\Eloquent\Model;

class Part extends Model
{
    use HasContent;
    public $timestamps = false;

    protected $fillable = [
        'slug',
        'location',
        'content'
    ];

    protected $casts = [
        'content' => 'array',
        'content.content' => 'array'
    ];

    public function render()
    {
        $part_views = config('filament-cms.part_views');
        return view($part_views($this->location))->with([
            'part' => $this
        ])->render();
    }
}
