<?php

namespace Titantwentyone\FilamentCMS\Commands;

use Filament\Support\Commands\Concerns\CanManipulateFiles;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Titantwentyone\FilamentCMS\Commands\Composites\StubHandler;

class MakeContent extends \Illuminate\Console\Command
{
    protected $signature = 'make:content {model}';

    protected $description = 'Creates Filament CMS model and migration';

    private StubHandler $handler;

    public function handle(StubHandler $handler): void
    {
        $this->handler = $handler;

        $model = $this->argument('model');

        $model_name = $model;
        $model_fqn = "App\\Models\\$model";
        $model_namespace = "App\\Models";
        $model_path = '/';

        if(Str::of($model)->contains("\\")) { // is it namespaced?
            $exploded = explode("\\", $model);
            $model_name = collect($exploded)->last();
            $model_fqn = $model;
            array_pop($exploded);
            $model_namespace = collect($exploded)->join('\\');
            $model_path = Str::of(collect($exploded)->join('/'))->append('/');
        }

        $model = Str::of($model_name)->ucfirst();
        $model_plural = $model->plural();
        $table = $model_plural->lower();
        $view = Str::of($model)->lower()->slug();

        $this->handler->registerStubs([
            'model' => '/app/Models/model.stub',
            'filament-resource' => '/app/Filament/resource.stub',
            'filament-create' => '/app/Filament/create.stub',
            'filament-edit' => '/app/Filament/edit.stub',
            'filament-list' => '/app/Filament/list.stub',
            'model-migration' => '/database/migrations/migration.stub'
        ]);

        $this->handler->writeStub(
            'models',
            'model',
            "{$model_path}{$model}.php",
            [
                'model' => $model,
                'model_namespace' => $model_namespace,
                'prefix' => "/".$view->plural(),
                'view' => $view
            ]
        );

        $date = now()->format('Y_m_d_His');

        $this->handler->writeStub(
            'migrations',
            'model-migration',
            "{$date}_create_{$table}_table.php",
            [
                'table' => $table
            ]
        );

        $this->handler->writeStub(
            'filament',
            'filament-resource',
            "/{$model}Resource.php",
            [
                'namespace' => config('filament.resources.namespace'),
                'model' => $model,
                'model_fqn' => $model_fqn,
                'model_plural' => $model_plural
            ]
        );

        $pages_namespace = config('filament.resources.namespace')."\\{$model}Resource\\Pages";

        $this->handler->writeStub(
            'filament',
            'filament-create',
            "/{$model}Resource/Pages/Create{$model}.php",
            [
                'namespace' => $pages_namespace,
                'model' => $model,
                'resource' => config('filament.resources.namespace')."\\{$model}Resource"
            ]
        );

        $this->handler->writeStub(
            'filament',
            'filament-edit',
            "/{$model}Resource/Pages/Edit{$model}.php",
            [
                'namespace' => $pages_namespace,
                'model' => $model,
                'resource' => config('filament.resources.namespace')."\\{$model}Resource"
            ]
        );

        $this->handler->writeStub(
            'filament',
            'filament-list',
            "/{$model}Resource/Pages/List{$model_plural}.php",
            [
                'namespace' => $pages_namespace,
                'model' => $model,
                'model_plural' => $model_plural,
                'resource' => config('filament.resources.namespace')."\\{$model}Resource"
            ]
        );
    }
}