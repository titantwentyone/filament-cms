<?php

namespace Titantwentyone\FilamentCMS\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Tests\Fixtures\App\Domain\PartManager;
use Titantwentyone\FilamentCMS\Domain\Part\Contracts\Manager;

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
        $manager = app(Manager::class);

        $part_views = $manager->views();

        $view = Arr::normalize($part_views)[$this->location];

        return view($view)->with([
            'part' => $this
        ])->render();
    }
}
