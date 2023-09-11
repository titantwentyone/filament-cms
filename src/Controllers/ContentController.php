<?php

namespace Titantwentyone\FilamentCMS\Controllers;

class ContentController
{
    public function generateRoutes()
    {
        $content_routes = app('content_routes');

        $content_routes->each(function($prefix, $model) {

            $prefix = $prefix == "/" ? "" : $prefix;

            \Illuminate\Support\Facades\Route::get($prefix, function() use ($model) {
                $instance = $model::where('is_root', true)->firstOrFail();

                if(!\Illuminate\Support\Facades\Auth::guard('web')->check() && !$instance->is_published) {
                    abort(404);
                }

                return view($model::$view, array_merge([
                    'model' => $instance
                ], $instance->viewData()));
            })->middleware('web');

            \Illuminate\Support\Facades\Route::get($prefix."/{slug}", function($slug) use ($model) {
                $instance = $model::where('is_root', false)->where('slug', $slug)->firstOrFail();

                if(!\Illuminate\Support\Facades\Auth::guard('web')->check() && !$instance->is_published) {
                    abort(404);
                }

                return view($model::$view, array_merge([
                    'model' => $instance
                ], $instance->viewData()));
            })->middleware('web');

        });
    }
}