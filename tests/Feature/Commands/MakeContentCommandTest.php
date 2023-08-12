<?php

use Illuminate\Filesystem\Filesystem;

it('will make the necessary files', function() {

    $models = \Illuminate\Support\Facades\Storage::fake('models');
    $migrations = \Illuminate\Support\Facades\Storage::fake('migrations');
    $filament = \Illuminate\Support\Facades\Storage::fake('filament');

    app()->bind(\Titantwentyone\FilamentCMS\Commands\Composites\StubHandler::class, function($app) use ($models, $migrations, $filament) {
        return new \Titantwentyone\FilamentCMS\Commands\Composites\StubHandler([
            'models' => $models,
            'migrations' => $migrations,
            'filament' => $filament
        ],
        \Illuminate\Support\Facades\Storage::disk('filament_cms_stubs'));
    });

    //dd(config());

    $this->artisan("make:content Tests\\\\Fixtures\\\\App\\\\Models\\\\Page");

    $models->assertExists('Tests/Fixtures/App/Models/Page.php');
    expect($models->get('Tests/Fixtures/App/Models/Page.php'))
        ->toBe(file_get_contents(realpath(__DIR__.'/../../Fixtures/app/Models/Page.php')));

    //@todo make less sensitive to seconds
    $date = now()->format('Y_m_d_His');
    $migrations->assertExists("{$date}_create_pages_table.php");
    expect($migrations->get("{$date}_create_pages_table.php"))
        ->toBe(file_get_contents(realpath(__DIR__.'/../../Fixtures/database/migrations/2023_05_22_093206_create_pages_table.php')));

    //dd($filament->allFiles());
    $filament->assertExists("PageResource.php");
    expect($filament->get("PageResource.php"))
        ->toBe(file_get_contents(__DIR__.'/../../Fixtures/app/Filament/Resources/PageResource.php'));

    $filament->assertExists("PageResource/Pages/CreatePage.php");
    expect($filament->get("PageResource/Pages/CreatePage.php"))
        ->toBe(file_get_contents(__DIR__.'/../../Fixtures/app/Filament/Resources/PageResource/Pages/CreatePage.php'));

    $filament->assertExists("PageResource/Pages/EditPage.php");
    expect($filament->get("PageResource/Pages/EditPage.php"))
        ->toBe(file_get_contents(__DIR__.'/../../Fixtures/app/Filament/Resources/PageResource/Pages/EditPage.php'));

    $filament->assertExists("PageResource/Pages/ListPages.php");
    expect($filament->get("PageResource/Pages/ListPages.php"))
        ->toBe(file_get_contents(__DIR__.'/../../Fixtures/app/Filament/Resources/PageResource/Pages/ListPages.php'));

});