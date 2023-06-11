<?php

use Illuminate\Filesystem\Filesystem;

it('will make the necessary files', function() {

    $mock = $this->partialMock(\Illuminate\Filesystem\Filesystem::class, function(\Mockery\MockInterface $mock) {

        $mock->shouldReceive('put')
            ->with(app_path("Models/Page.php"), getModelContent())
            ->once();

        $date = now()->format('Y_m_d');
        $mock->shouldReceive('put')
            ->with(database_path("migrations/{$date}_create_pages_table.php"), getMigrationContent())
            ->once();

        $mock->shouldReceive('put')
            ->with(app_path("Filament/Resources/PageResource.php"), getPageResourceContent())
            ->once();

        $mock->shouldReceive('put')
            ->with(app_path("Filament/Resources/PageResource/Pages/CreatePage.php"), getCreatePageContent())
            ->once();

        $mock->shouldReceive('put')
            ->with(app_path("Filament/Resources/PageResource/Pages/EditPage.php"), getEditPageContent())
            ->once();

        $mock->shouldReceive('put')
            ->with(app_path("Filament/Resources/PageResource/Pages/ListPages.php"), getListPagesContent())
            ->once();

    });

    app()->bind(Filesystem::class, fn() => $mock);

    $this->artisan('make:content Page');
});

function getModelContent()
{
    return "<?php

namespace App\Models;

use Filament\Forms\Components\RichEditor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Titantwentyone\FilamentCMS\Contracts\Content;

class Page extends Content
{
    use SoftDeletes;

    public static \$prefix = '/pages';
    public static \$view = 'page';

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
        Schema::create('pages', function (Blueprint \$table) {
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

function getPageResourceContent()
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
use App\Models\Page;
use App\Filament\Resources\PageResource\Pages;

class PageResource extends Resource
{
    protected static ?string \$model = Page::class;

    protected static ?string \$navigationIcon = 'heroicon-o-collection';

    public static function form(Form \$form): Form
    {
        return \$form
            ->schema([
                Forms\Components\Grid::make()
                    ->columns(1)
                    ->schema(static::headerForm()),
                Forms\Components\Grid::make()
                    ->columns(1)
                    ->schema(Page::form()),
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
            'index' => Pages\ListPages::route('/'),
            'create' => Pages\CreatePage::route('/create'),
            'edit' => Pages\EditPage::route('/{record}/edit'),
        ];
    }    
}";
}

function getCreatePageContent()
{
    return "<?php

namespace App\Filament\Resources\PageResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\PageResource;

class CreatePage extends CreateRecord
{
    protected static string \$resource = PageResource::class;
}";
}

function getEditPageContent()
{
    return "<?php

namespace App\Filament\Resources\PageResource\Pages;

use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\PageResource;

class EditPage extends EditRecord
{
    protected static string \$resource = PageResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}";
}

function getListPagesContent()
{
    return "<?php

namespace App\Filament\Resources\PageResource\Pages;

use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\PageResource;

class ListPages extends ListRecords
{
    protected static string \$resource = PageResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}";
}