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

    $this->artisan('make:content Post');

    $models->assertExists('Post.php');
    expect($models->get('Post.php'))->toBe(getModelContent());

    $date = now()->format('Y_m_d');
    $migrations->assertExists("{$date}_create_posts_table.php");
    expect($migrations->get("{$date}_create_posts_table.php"))->toBe(getMigrationContent());

    $filament->assertExists("Resources/PostResource.php");
    expect($filament->get("Resources/PostResource.php"))->toBe(getPostResourceContent());

    $filament->assertExists("Resources/PostResource/Pages/CreatePost.php");
    expect($filament->get("Resources/PostResource/Pages/CreatePost.php"))->toBe(getCreatePostContent());

    $filament->assertExists("Resources/PostResource/Pages/EditPost.php");
    expect($filament->get("Resources/PostResource/Pages/EditPost.php"))->toBe(getEditPostContent());

    $filament->assertExists("Resources/PostResource/Pages/ListPosts.php");
    expect($filament->get("Resources/PostResource/Pages/ListPosts.php"))->toBe(getListPostsContent());

});

function getModelContent()
{
    return "<?php

namespace App\Models;

use Filament\Forms\Components\RichEditor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Titantwentyone\FilamentCMS\Contracts\Content;

class Post extends Content
{
    use SoftDeletes;

    public static \$prefix = '/posts';
    public static \$view = 'post';

    protected \$fillable = [
        'title',
        'slug',
        'content',
        'created_at',
        'is_published',
        'is_root',
        'updated_at'
    ];

    public static function form(): array
    {
        return [
            RichEditor::make('content')
        ];
    }
}";
}

function getMigrationContent()
{
    return "<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('posts', function (Blueprint \$table) {
            \$table->id();
            \$table->string('slug');
            \$table->longText('content')->nullable();
            \$table->string('title')->required();
            \$table->boolean('is_published')->default(false);
            \$table->boolean('is_root')->default(false);
            \$table->softDeletes();
            \$table->timestamps();
        });
    }
};";
}

function getPostResourceContent()
{
    return "<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Support\Str;
use App\Models\Post;
use App\Filament\Resources\PostResource\Pages;

class PostResource extends Resource
{
    protected static ?string \$model = Post::class;

    protected static ?string \$navigationIcon = 'heroicon-o-collection';

    protected static ?string \$navigationGroup = 'content';

    public static string \$contentField = 'content';

    public static function form(Form \$form): Form
    {
        return \$form
            ->schema([
                Forms\Components\Grid::make()
                    ->columns(1)
                    ->schema(static::headerForm()),
                Forms\Components\Grid::make()
                    ->columns(1)
                    ->schema(Post::form()),
                Forms\Components\Grid::make()
                    ->columns(1)
                    ->schema(static::footerForm())
            ]);
    }

    private static function headerForm(): array
    {
        return [
            Forms\Components\TextInput::make('title')
                ->required()
                ->reactive()
                ->afterStateUpdated(function(\$state, \$set, \$livewire) {
                    if(\$livewire instanceof CreateRecord) {
                        \$set('slug', Str::slug(\$state));
                    }
                }),
            Forms\Components\Toggle::make('is_root'),
            Forms\Components\TextInput::make('slug')
                ->required()
                ->unique(ignoreRecord: true)
        ];
    }

    private static function footerForm(): array
    {
        return [
            Forms\Components\DatePicker::make('created_at')
                ->required()
                ->default(fn() => now()),
            Forms\Components\Toggle::make('is_published')
                ->default(false)
        ];
    }

    public static function table(Table \$table): Table
    {
        return \$table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
    
    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }    
}";
}

function getCreatePostContent()
{
    return "<?php

namespace App\Filament\Resources\PostResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\PostResource;
use Titantwentyone\FilamentCMS\Filament\Resources\Concerns\RendersView;

class CreatePost extends CreateRecord
{
    use RendersView;

    protected static string \$resource = PostResource::class;
}";
}

function getEditPostContent()
{
    return "<?php

namespace App\Filament\Resources\PostResource\Pages;

use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\PostResource;
use Titantwentyone\FilamentCMS\Filament\Resources\Concerns\RendersView;

class EditPost extends EditRecord
{
    use RendersView;

    protected static string \$resource = PostResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}";
}

function getListPostsContent()
{
    return "<?php

namespace App\Filament\Resources\PostResource\Pages;

use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\PostResource;

class ListPosts extends ListRecords
{
    protected static string \$resource = PostResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}";
}