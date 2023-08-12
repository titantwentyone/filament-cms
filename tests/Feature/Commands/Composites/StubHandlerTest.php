<?php

it('will throw an exception if the stub file does not exist', function() {

    $models = \Illuminate\Support\Facades\Storage::fake('models');

    $stubs = \Illuminate\Support\Facades\Storage::fake('stubs');
    $stubs->put('/test.stub', 'test stub');

    $handler = new \Titantwentyone\FilamentCMS\Commands\Composites\StubHandler([
        'models' => $models
    ], $stubs);

    $handler->registerStubs([
        'first_stub' => '/test.stub'
    ]);

    $handler->registerStubs([
        'second_stub' => '/does_not_exist.stub'
    ]);
})
->expectExceptionMessage('The stub file /does_not_exist.stub does not exist')
->expectException(Exception::class);

it('will generate a file from a stub', function () {

    $models = \Illuminate\Support\Facades\Storage::fake('models');

    $stubs = \Illuminate\Support\Facades\Storage::fake('stubs');
    $stubs->put('/test.stub', 'test stub');

    $handler = new \Titantwentyone\FilamentCMS\Commands\Composites\StubHandler([
        'models' => $models
    ], $stubs);

    $handler->registerStubs([
        'first_stub' => '/test.stub'
    ]);

    $handler->writeStub('models', 'first_stub', '/Models/Test.php', []);

    \Illuminate\Support\Facades\Storage::disk('models')->assertExists('Models/Test.php');
});

it('will replace terms in a file', function () {

    $models = \Illuminate\Support\Facades\Storage::fake('models');

    $stubs = \Illuminate\Support\Facades\Storage::fake('stubs');
    $stubs->put('/test.stub', '{{ to_replace }} {{ not_replaced }} {{ to_replace_again }}');

    $handler = new \Titantwentyone\FilamentCMS\Commands\Composites\StubHandler([
        'models' => $models
    ], $stubs);

    $handler->registerStubs([
        'first_stub' => '/test.stub'
    ]);

    $handler->writeStub('models', 'first_stub', '/Models/Test.php', [
        'to_replace' => 'replaced',
        'to_replace_again' => 'replaced again'
    ]);

    \Illuminate\Support\Facades\Storage::disk('models')->assertExists('Models/Test.php');
    expect(\Illuminate\Support\Facades\Storage::disk('models')->get('Models/Test.php'))->toBe('replaced {{ not_replaced }} replaced again');
});

it('will throw an exception if the file being written to already exists', function () {
    $models = \Illuminate\Support\Facades\Storage::fake('models');

    $stubs = \Illuminate\Support\Facades\Storage::fake('stubs');

    $models->put('TestModel.php', '');

    $handler = new \Titantwentyone\FilamentCMS\Commands\Composites\StubHandler([
        'models' => $models
    ], $stubs);

    $handler->writeStub(
        'models',
        'stub',
        'TestModel.php',
        []
    );
})
->expectExceptionMessage('file TestModel.php already exists')
->expectException(Exception::class);

it('will throw an exception if the stub was not registered', function () {
    $models = \Illuminate\Support\Facades\Storage::fake('models');

    $stubs = \Illuminate\Support\Facades\Storage::fake('stubs');

    //$models->put('TestModel.php', '');

    $handler = new \Titantwentyone\FilamentCMS\Commands\Composites\StubHandler([
        'models' => $models
    ], $stubs);

    $handler->writeStub(
        'models',
        'stub',
        'TestModel.php',
        []
    );
})
    ->expectExceptionMessage('stub is not registered')
    ->expectException(Exception::class);