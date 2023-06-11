<?php

it('will create temp files in storage for css parsing', function() {

    \Illuminate\Support\Facades\Storage::fake();

    $page = \Tests\Skeleton\app\Models\Page::create([
        'title' => 'Test Page',
        'slug' => 'test-page',
        'is_published' => true,
        'content' => 'hello'
    ]);

    $new_page = \Tests\Skeleton\app\Models\Page::make([
        'title' => 'Test Page',
        'slug' => 'test-page',
        'is_published' => true,
        'content' => 'new_page'
    ]);

    $user = \Tests\Skeleton\app\Models\User::create([
        'name' => 'Test User',
        'email' => 'test@test.com',
        'password' => \Illuminate\Support\Facades\Hash::make('password')
    ]);

    \Pest\Livewire\livewire(\Tests\Skeleton\app\Filament\Resources\PageResource\Pages\EditPage::class, [
        'record' => $page->getKey()
    ])
        ->fillForm($new_page->attributesToArray())
        ->call('save');

    expect(\Tests\Skeleton\app\Models\Page::count())->toBe(1);

    //$this->assertFileExists(storage_path('cms/pages/test-page.blade.php'));
    $disk = \Illuminate\Support\Facades\Storage::build([
        'driver' => 'local',
        'root' => storage_path('/cms')
    ]);

    expect($disk->exists('/test-page.blade.php'))->toBeTrue();
    expect($disk->get('/test-page.blade.php'))->toBe('new_page');

});