<?php

namespace Titantwentyone\FilamentCMS\Contracts;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;

abstract class Content extends \Illuminate\Database\Eloquent\Model
{
    public function url(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->is_root ? static::$prefix : static::$prefix.$this->slug
        );
    }

    protected static function booted()
    {
        self::saved(function($content) {
            if($content->is_root) {
                static::where('is_root', true)
                    ->where('id', '!=', $content->id)
                    ->update(['is_root' => false]);
            }
        });
    }

    public function scopePublished(Builder $query) : void
    {
        $query->where('is_published', true);
    }

    public function viewData() : array
    {
        return [];
    }

    public static function hasRouteArguments() : array
    {
        return [];
    }
}