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

Note that your routes are processed after the packages routes.

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