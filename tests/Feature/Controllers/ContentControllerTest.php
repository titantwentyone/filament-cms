<?php

it('will route to the correct view', function() {

    Tests\Fixtures\App\Models\Page::create([
        'title' => 'Testing',
        'slug' => 'testing',
        'is_published' => true
    ]);

    Tests\Fixtures\App\Models\Post::create([
        'title' => 'A blog post',
        'slug' => 'first',
        'is_published' => true
    ]);

    $this->get('/pages/testing')
        ->assertSuccessful()
        ->assertViewIs('page');

    $this->get('/first')
        ->assertNotFound();

    $this->get('/blog/first')
        ->assertSuccessful()
        ->assertViewIs('post');

})
->covers(\Titantwentyone\FilamentCMS\Controllers\ContentController::class);

it('will route to a root view', function () {

    \Tests\Fixtures\App\Models\PageWithRootPrefix::create([
        'title' => 'Testing',
        'slug' => 'testing',
        'is_published' => true,
        'is_root' => true
    ]);

    $this->get('/testing')
        ->assertNotFound();

    $this->get('/')
        ->assertSuccessful();

})
->covers(\Titantwentyone\FilamentCMS\Controllers\ContentController::class);

it('will serve 404 if page not published', function () {

    \Tests\Fixtures\App\Models\Page::create([
        'title' => 'Testing',
        'slug' => 'testing',
        'is_published' => false
    ]);

    $this->get('/testing')
        ->assertNotFound();
})
->covers(\Titantwentyone\FilamentCMS\Controllers\ContentController::class);

it('will serve 404 if page root not published', function () {

    \Tests\Fixtures\App\Models\PageWithRootPrefix::create([
        'title' => 'Testing',
        'slug' => 'testing',
        'is_published' => false,
        'is_root' => false
    ]);

    $this->get('/testing')
        ->assertNotFound();
})
->covers(\Titantwentyone\FilamentCMS\Controllers\ContentController::class);

test('content is overriden by higher root content', function () {
    //for example /news root will override content with prefix of /news

    $root_content = \Tests\Fixtures\App\Models\PageWithRootPrefix::create([
        'title' => 'Testing',
        'slug' => 'blog',
        'is_published' => true,
        'is_root' => false
    ]);

    $post_content = Tests\Fixtures\App\Models\Post::create([
        'title' => 'A blog post',
        'slug' => 'first',
        'is_published' => true,
        'is_root' => true
    ]);

    $this->get('/blog')
        ->assertViewHas('model', $post_content)
        ->assertSuccessful();
});

it('can supply arguments to a path', function () {

    $post_content = Tests\Fixtures\App\Models\Post::create([
        'title' => 'A blog post',
        'slug' => 'first',
        'is_published' => true,
        'is_root' => false
    ]);

    $content_with_args = \Tests\Fixtures\App\Models\PageWithArgs::create([
        'title' => 'Testing',
        'slug' => 'blog',
        'is_published' => true,
        'is_root' => false,
        'uses_route_parameters' => true
    ]);

    $router = app(\Illuminate\Routing\Router::class);

    //$this->withoutExceptionHandling();

    $this->get('/pages_with_args/blog/page/1')
        ->assertViewHas('model', $content_with_args)
        ->assertViewHas('page', 1)
        ->assertSuccessful();

    $this->get('/pages_with_args/blog/foo/something')
        ->assertViewHas('model', $content_with_args)
        ->assertViewHas('bar', 'something')
        ->assertSuccessful();

});