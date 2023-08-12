# Filament CMS

Package to provide basic CMS functionality to a Filament powered Laravel app.

To get started, the following command will create a model, migration and necessary Filament resources:
```
php artisan make:content Page
```
Once generated, you should add the model to the config:
```
return [
    'models' => [
        App\Models\Page::class
    ]
];
```
You can publish the config with:
```
php artisan vendor:publish --tag=filament-cms-config
```
By default, pages are prefixed and the prefix is defined in the model:
```php
public static $prefix = '/pages';
```
If you wish the content to be placed at the root of the site, amend to:
```php
public static $prefix = '/';
```
The view of the page is not created when creating content. This should be created and placed in `/resources/views`. The models attribute `view` is used to specify this view:
```php
public static $view = 'page' // indicates a view located at /resources/views/page.blade.php
```
You can specify that content is the root by using the "Is root" toggle switch in Filament:

![is_root.png](docs%2Fimages%2Fis_root.png)

For content defined as root, this is the homepage where the `prefix` is `/`. For content defined with a prefix of `/pages` this page is
used at that path (e.g. yoursite.com/pages).

Note that your app routes are processed after the packages routes.

When the view is called, it is passed the instance of model through its `$model` view attribute. In the view you can access any property provided.
```php
{{ $model->title }}
{!! $model->content }}
```
Content models provide the following default fields:

- `title`
- `is_root`
- `slug`

When creating new content, Filament will attempt to generate a new slug for you based on the title

The `content` field is provided as part of the models method `form`:

```php
public static function form(): array
{
    return [
        RichEditor::make('content')
    ];
}
```
You can add any filament field you wish here and this can then be accessed via the model. Remember to update
your migration and models `fillable` attribute.

## Content Parts
Sometimes you may want to reuse content throught a site in places such as footers or headers.
To do this, you can create "parts" which take a location and content from fields you define.

You can specify the locations you want to be provided in the config:

```php
'part_locations' => [
    'footer' => 'Footer',
    'header' => 'Header'
]
```
You can specify the field or fields to be used for these content parts with the config option `part_fields`:

```php
'part_fields' => [
    \Filament\Forms\Components\TextInput::make('test')
]
```
`part_fields` can also be provided as a closure, the first argument of which is the `location`. This allows you to provide
different field schemas dependening on the location in which they are to be used:

```php
'part_fields' => function($location) {
    return match($location) {
        'footer' => [\Filament\Forms\Components\TextInput::make('test')],
        'header' => [\Filament\Forms\Components\TextInput::make('another_test')],
        default => []
    };
}
```
To render the content on the frontend, use the helper function `content_part($location)`

## Dynamic rendering
When saving a content item or part, the CMS will render your pages into a location in `storage` and then run
`npm vite build` which will compile any tailwind classes that you may have used within the content data.

By default the location of the rendered content is set as `/storage/cms`. You should add this to your `content` array in `tailwind.config.js`:

```js
module.exports = {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
        './storage/cms/**/*.blade.php'   
    ]
}
```

You can change the location of the cms storage with the config setting `dynamic_render_location`:

```php
'dynamic_render_location` => base_path('/storage/different-cms-directory')
```

This does result in a slower time to save. You can turn off this functionality by amendign the config:

```php
'dynamic_render' => false
```