<?php

namespace Titantwentyone\FilamentCMS\Models;

use Illuminate\Database\Eloquent\Model;

class Part extends Model
{
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

        if(is_callable($part_views)) {
            $view = $part_views($this->location);
        } else {
            $view = $part_views[$this->location];
        }

        return view($view)->with([
            'part' => $this
        ])->render();
    }
}
