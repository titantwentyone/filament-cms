<?php

(new \Titantwentyone\FilamentCMS\Controllers\ContentController())->generateRoutes();

//$routes = collect(config('filament-cms.models'));
//
//$routes->each(function($model) {
//
//    $prefix = $model::$prefix == "/" ? "" : $model::$prefix;
//
//    \Illuminate\Support\Facades\Route::get($model::$prefix, function() use ($model) {
//        $instance = $model::where('is_root', true)->firstOrFail();
//
//        if(!\Illuminate\Support\Facades\Auth::guard('web')->check() && !$instance->is_published) {
//            abort(404);
//        }
//
//        return view($model::$view, [
//            'model' => $instance
//        ]);
//    })->middleware('web');
//
//    \Illuminate\Support\Facades\Route::get($prefix."/{slug}", function($slug) use ($model) {
//        $instance = $model::where('is_root', false)->where('slug', $slug)->firstOrFail();
//
//        if(!\Illuminate\Support\Facades\Auth::guard('web')->check() && !$instance->is_published) {
//            abort(404);
//        }
//
//        return view($model::$view, [
//            'model' => $instance
//        ]);
//    })->middleware('web');
//
//});