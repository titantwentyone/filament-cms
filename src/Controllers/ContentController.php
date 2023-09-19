<?php

namespace Titantwentyone\FilamentCMS\Controllers;

use Titantwentyone\FilamentCMS\Contracts\Content;

class ContentController
{
    public function generateRoutes()
    {
        $content_routes = app('content_routes');

        $content_routes->each(function($prefix, $model) use ($content_routes) {

            $prefix = $prefix == "/" ? "" : $prefix;

            \Illuminate\Support\Facades\Route::get($prefix, function() use ($model) {
                return $this->renderRoot($model);
            })->middleware('web');


                \Illuminate\Support\Facades\Route::get($prefix."/{slug}", function($slug) use ($model, $content_routes, $prefix) {

                    $instance = $this->getContent($model, $slug);

                    return $this->render($instance, $model, $slug);

                })->middleware('web');
            if(count($model::hasRouteArguments())) {
//                $args = collect($model::hasRouteArguments())->map(function($arg) {
//                    return "{{$arg}}";
//                })->implode('/');

                $arguments = $model::hasRouteArguments();

                collect($arguments)->each(function($params, $pre) use ($model, $content_routes, $prefix) {

                    $args = collect($params)->map(function($arg) {
                        return "{{$arg}}";
                    })->implode('/');

                    \Illuminate\Support\Facades\Route::get($prefix."/{slug}/".$pre."/".$args, function($slug, ...$params) use ($model, $content_routes, $prefix, $pre) {
                        $instance = $this->getContent($model, $slug);

                        //$params = [];

                        if($instance && !$instance->uses_route_parameters && count($params)) {
                            //$this->render($instance, $model, $slug);
                            abort(404);
                        }

                        $params = array_combine($model::hasRouteArguments()[$pre], array_pad($params, count($model::hasRouteArguments()[$pre]), null));

                        return $this->render($instance, $model, $slug, $params);

                    })->middleware('web');
                });


            }

            //}

        });
    }

    private function renderRoot(string $model)
    {
        $instance = $this->getContent($model);

        return $this->render($instance, $model);
    }

    private function render(?Content $instance, string $model, string $slug = '', array $params = [])
    {
        if($instance) {
            $this->abortIfNotPublished($instance);

            $data = array_merge([
                'model' => $instance,
                ...$params
            ], $instance->viewData());

            return view($model::$view, $data);
        } else {
            //check if root of other content model
            $content_routes = app('content_routes');

            if(in_array($model::$prefix."/".$slug, array_values($content_routes->toArray()))) {

                $model = array_search($model::$prefix."/".$slug, $content_routes->toArray());

                return $this->renderRoot($model);
            }
        }

        abort(404);
    }

    private function getContent(string $model, string $slug = '') : ?Content
    {
        $is_root = !(bool) $slug;

        $instance = $model::where('is_root', $is_root)
            ->when(!$is_root, function($query) use ($slug) {
                return $query->where('slug', $slug);
            })
            ->first();

        app()->bind(Content::class, fn() => $instance);

        return $instance;

    }

    private function abortIfNotPublished(Content $instance) : void
    {
        if(!$instance->is_published) {
            abort(404);
        }
    }
}