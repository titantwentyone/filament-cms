<?php

it('will provide the correct url', function() {

    $page_class = new class extends \Titantwentyone\FilamentCMS\Contracts\Content {
        public static $prefix = '/';

        protected $fillable = [
            'title',
            'slug',
            'content',
            'created_at',
            'is_published'
        ];
    };

    $page = $page_class::make([
        'slug' => 'testing'
    ]);

    expect($page->url)->toBe('/testing');

    $page->is_root = true;

    expect($page->url)->toBe('/');
});

it('there can only be one root item', function () {

    $page1 = Tests\Fixtures\App\Models\Page::create([
        'title' => 'Homepage',
        'slug' => 'homepage',
        'is_root' => true
    ]);

    $page2 = Tests\Fixtures\App\Models\Page::create([
        'title' => 'Not Homepage',
        'slug' => 'not-homepage',
        'is_root' => false
    ]);

    expect($page1->is_root)->toBeTrue();
    expect($page2->is_root)->toBeFalse();

    $page2->is_root = true;
    $page2->save();

    $page1->refresh();

    expect($page2->is_root)->toBeTrue();
    expect($page1->is_root)->toBeFalsy();

});

it('will show the page if published', function() {

    $page1 = Tests\Fixtures\App\Models\Page::create([
        'title' => 'Homepage',
        'slug' => 'homepage',
        'is_root' => true,
        'is_published' => true
    ]);

    $this->get('/pages')->assertSuccessful();

    $page1->is_published = false;
    $page1->save();

    $this->get('/pages')->assertNotFound();
});

it('will show the page if not published but logged in', function() {

    $page1 = Tests\Fixtures\App\Models\Page::create([
        'title' => 'Homepage',
        'slug' => 'homepage',
        'is_root' => true,
        'is_published' => false
    ]);

    $this->get('/pages')->assertNotFound();

    $user = Tests\Fixtures\App\Models\User::create([
        'name' => 'Test User',
        'email' => 'test@test.com',
        'password' => \Illuminate\Support\Facades\Hash::make('password')
    ]);

    $this->be($user, 'web');

    $this->get('/pages')->assertSuccessful();
});