<?php

namespace Titantwentyone\FilamentCMS\Commands;

use Filament\Support\Commands\Concerns\CanManipulateFiles;
use Illuminate\Support\Str;

class MakeContent extends \Illuminate\Console\Command
{
    use CanManipulateFiles;

    protected $signature = 'make:content {model}';

    protected $description = 'Creates Filament CMS model and migration';

    public function handle(): void
    {
        $model = Str::of($this->argument('model'))->ucfirst();
        $model_fqn = "App\\Models\\$model";
        $model_plural = $model->plural();
        $table = $model_plural->lower();
        $view = Str::of($model)->lower()->slug();

        //$model_fqn = "\\App\\Models\\$class";

//        if(class_exists($model_fqn)) {
//            $this->error('The class already exists');
//        }
//
//        $filament_resource_dir = "\\App\\Filament\\Resources\\{$class}Resource\\";
//        $filament_resource_pages_dir = "{$filament_resource_dir}\\Pages\\";
//
//        $filament_resource_fqn = "{$class}Resource.php";
//        $filament_create_page_fqn = "{$filament_resource_pages_dir}Create{$class}.php";
//        $filament_edit_page_fqn = "{$filament_resource_pages_dir}Edit{$class}.php";
//
//        $pluralClass = Str::of($class)->plural();
//        $filament_list_page_fqn = "{$filament_resource_pages_dir}List{$pluralClass}.php";

        $this->copyStubToApp(
            'src/Models/model',
            app_path("Models/$model.php"),
            [
                'model' => $model,
                'prefix' => "/".$view->plural(),
                'view' => $view
            ]
        );

        $date = now()->format('Y_m_d');

        $this->copyStubToApp(
            'database/migrations/migration',
            database_path("migrations/{$date}_create_{$table}_table.php"),
            ['table' => $table]
        );

        $this->copyStubToApp(
            'src/Filament/resource',
            app_path("Filament/Resources/{$model}Resource.php"),
            [
                'namespace' => 'App\Filament\Resources',
                'model' => $model,
                'model_fqn' => $model_fqn,
                'model_plural' => $model_plural
            ]
        );

        $this->copyStubToApp(
            'src/Filament/create',
            app_path("Filament/Resources/PageResource/Pages/Create{$model}.php"),
            [
                'namespace' => "App\Filament\Resources\\{$model}Resource\Pages",
                'model' => $model
            ]
        );

        $this->copyStubToApp(
            'src/Filament/edit',
            app_path("Filament/Resources/PageResource/Pages/Edit{$model}.php"),
            [
                'namespace' => "App\Filament\Resources\\{$model}Resource\Pages",
                'model' => $model
            ]
        );

        $this->copyStubToApp(
            'src/Filament/list',
            app_path("Filament/Resources/PageResource/Pages/List{$model_plural}.php"),
            [
                'namespace' => "App\Filament\Resources\\{$model}Resource\Pages",
                'model' => $model,
                'model_plural' => $model_plural
            ]
        );
    }
}