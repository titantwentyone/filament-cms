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
})
->covers(\Titantwentyone\FilamentCMS\Contracts\Content::class);

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

})
->covers(\Titantwentyone\FilamentCMS\Contracts\Content::class);

it('can scope content by its published state', function () {

    $page1 = Tests\Fixtures\App\Models\Page::create([
        'title' => 'Published',
        'slug' => 'published',
        'is_published' => true
    ]);

    $page2 = Tests\Fixtures\App\Models\Page::create([
        'title' => 'Not Published',
        'slug' => 'not-published',
        'is_published' => false
    ]);

    expect(\Tests\Fixtures\App\Models\Page::all()->count())->toBe(2);
    expect(\Tests\Fixtures\App\Models\Page::published()->count())->toBe(1);
    expect(\Tests\Fixtures\App\Models\Page::published()->first()->title)->toBe('Published');
})
->covers(\Titantwentyone\FilamentCMS\Contracts\Content::class);

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
})
->skip(message: 'Covered in ContentControllerTest')
->covers(\Tests\Fixtures\App\Models\Page::class);

it('will show the page if not published but logged in', function() {

    //@todo add banner to page to state "unpublished"?
    $page1 = Tests\Fixtures\App\Models\Page::create([
        'title' => 'Homepage',
        'slug' => 'homepage',
        'is_root' => true,
        'is_published' => false
    ]);

    $page2 = Tests\Fixtures\App\Models\Page::create([
        'title' => 'Another page',
        'slug' => 'another-page',
        'is_root' => false,
        'is_published' => false
    ]);

    $this->get('/pages')->assertNotFound();
    $this->get('/pages/another-page')->assertNotFound();

    $user = Tests\Fixtures\App\Models\User::create([
        'name' => 'Test User',
        'email' => 'test@test.com',
        'password' => \Illuminate\Support\Facades\Hash::make('password')
    ]);

    $this->be($user, 'web');

    $this->get('/pages')->assertSuccessful();
    $this->get('/pages/another-page')->assertSuccessful();
})
->skip(message: 'Covered in ContentControllerTest')
->covers(\Tests\Fixtures\App\Models\Page::class);