<?php

namespace Titantwentyone\FilamentCMS\Commands;

use Filament\Support\Commands\Concerns\CanManipulateFiles;
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

        $model = Str::of($this->argument('model'))->ucfirst();
        $model_fqn = "App\\Models\\$model";
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
            "$model.php",
            [
                'model' => $model,
                'prefix' => "/".$view->plural(),
                'view' => $view
            ]
        );

        $date = now()->format('Y_m_d');

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
            "Resources/{$model}Resource.php",
            [
                'namespace' => 'App\Filament\Resources',
                'model' => $model,
                'model_fqn' => $model_fqn,
                'model_plural' => $model_plural
            ]
        );

        $this->handler->writeStub(
            'filament',
            'filament-create',
            "Resources/{$model}Resource/Pages/Create{$model}.php",
            [
                'namespace' => "App\Filament\Resources\\{$model}Resource\Pages",
                'model' => $model
            ]
        );

        $this->handler->writeStub(
            'filament',
            'filament-edit',
            "Resources/{$model}Resource/Pages/Edit{$model}.php",
            [
                'namespace' => "App\Filament\Resources\\{$model}Resource\Pages",
                'model' => $model
            ]
        );

        $this->handler->writeStub(
            'filament',
            'filament-list',
            "Resources/{$model}Resource/Pages/List{$model_plural}.php",
            [
                'namespace' => "App\Filament\Resources\\{$model}Resource\Pages",
                'model' => $model,
                'model_plural' => $model_plural
            ]
        );
    }
}