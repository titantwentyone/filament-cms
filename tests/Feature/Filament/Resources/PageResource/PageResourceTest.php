<?php

it('will generate a slug for content if the content is being created', function () {

    $page = \Tests\Fixtures\App\Models\Page::make([
        'title' => 'Testing',
        'slug' => 'testing',
        'is_published' => true
    ]);

    \Pest\Livewire\livewire(\Tests\Fixtures\App\Filament\Resources\PageResource\Pages\CreatePage::class)
        ->fillForm($page->attributesToArray())
        ->set('data.title', 'A Different Title')
        ->assertSet('data.slug', 'a-different-title');
})
->covers(\Tests\Fixtures\App\Filament\Resources\PageResource::class);

it('will show a table of content', function () {

    $page = \Tests\Fixtures\App\Models\Page::create([
        'title' => 'Testing',
        'slug' => 'testing',
        'is_published' => true
    ]);

    \Pest\Livewire\livewire(\Tests\Fixtures\App\Filament\Resources\PageResource\Pages\ListPages::class)
        ->assertCanSeeTableRecords(\Tests\Fixtures\App\Models\Page::all())
        ->assertTableColumnExists('title')
        ->assertTableColumnStateSet('title', 'Testing', $page->id);

})
->covers(\Tests\Fixtures\App\Filament\Resources\PageResource::class);

it('has the header form', function () {

    \Pest\Livewire\livewire(\Tests\Fixtures\App\Filament\Resources\PageResource\Pages\CreatePage::class)
        ->assertFormFieldExists('title',function(\Filament\Forms\Components\TextInput $field) {
            return $field->isRequired() &&
                $field->reactive();
        })
        ->assertFormFieldExists('is_root', function(\Filament\Forms\Components\Toggle $field) {
            //confirms it is a toggle button
            return true;
        })
        ->assertFormFieldExists('slug', function(\Filament\Forms\Components\TextInput $field) {
            return $field->isRequired() &&
                in_array(new \Illuminate\Validation\Rules\Unique('pages', 'slug'), $field->getValidationRules());
        });

});

it('will update the slug', function () {

    \Pest\Livewire\livewire(\Tests\Fixtures\App\Filament\Resources\PageResource\Pages\CreatePage::class)
        ->fillForm([
            'title' => 'Test Page'
        ])
        ->assertSet('data.slug', 'test-page');
});

it('has the footer form', function () {

    \Pest\Livewire\livewire(\Tests\Fixtures\App\Filament\Resources\PageResource\Pages\CreatePage::class)
        ->assertFormFieldExists('created_at', function(\Filament\Forms\Components\DatePicker $field) {
            return $field->isRequired() &&
                $field->getDefaultState()->format('d M Y H:i:s') == \Illuminate\Support\Carbon::now()->format('d M Y H:i:s');
        })
        ->assertFormFieldExists('is_published', function(\Filament\Forms\Components\Toggle $field) {
            return $field->getDefaultState() == false;
        });
});

it('displays the table', function () {

    $page = \Tests\Fixtures\App\Models\Page::create([
        'title' => 'Test Page',
        'slug' => 'test-page'
    ]);

    \Pest\Livewire\livewire(\Tests\Fixtures\App\Filament\Resources\PageResource\Pages\ListPages::class)
        ->assertCanSeeTableRecords(collect([$page]))
        ->assertTableColumnExists('title', function (\Filament\Tables\Columns\TextColumn $column) {
            return $column->isSearchable() &&
                $column->isSortable();
        })
        ->assertTableColumnExists('is_root', function(\Filament\Forms\Components\Toggle $field) {
            return $field->getOnIcon() == 'heroicon-o-home' &&
                $field->getOffIcon() == 'heroicon-o-home' &&
                $field->getOnColor() == 'success';
        })
        ->assertTableColumnExists('is_published', function(\Filament\Forms\Components\Toggle $field) {
            return $field->getOnIcon() == 'heroicon-o-bolt' &&
                $field->getOffIcon() == 'heroicon-o-bolt' &&
                $field->getOnColor() == 'success';
        })
        ->assertTableActionExists('edit')
        ->assertTableBulkActionExists('delete');

});