<?php

namespace App\Filament\Resources\News;

use BackedEnum;
use App\Models\News;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Schemas\Schema;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Resource;
use Filament\Actions\DeleteAction;
use Filament\Support\Icons\Heroicon;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Utilities\Set;
use App\Filament\Resources\News\Pages\EditNews;
use App\Filament\Resources\News\Pages\ListNews;
use App\Filament\Resources\News\Pages\CreateNews;
use App\Filament\Resources\News\Schemas\NewsForm;
use App\Filament\Resources\News\Tables\NewsTable;
use Filament\Tables\Columns\ToggleColumn;

class NewsResource extends Resource
{
    protected static ?string $model = News::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Newspaper;

    protected static ?string $recordTitleAttribute = 'News';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('author_id')
                    ->relationship('author', 'name')
                    ->required(),
                Select::make('news_category_id')
                    ->relationship('newsCategory', 'title')
                    ->required(),
                TextInput::make('title')
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn(Set $set, ?string $state) => $set('slug', Str::slug($state)))
                    ->required(),
                TextInput::make('slug')->readOnly(),
                FileUpload::make('thumbnail')->directory('file-thumbnail')
                    ->visibility('public')->image()->required()->columnSpanFull(),
                RichEditor::make('content')
                    ->columnSpanFull()
                    ->required(),
                Toggle::make('is_featured'),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('thumbnail')
                    ->label('Image')
                    ->circular()->visibility('public')
                    ->width(50),
                TextColumn::make('author.name')->sortable()->searchable()->label('Author'),
                TextColumn::make('newsCategory.title')->sortable()->searchable()->label('Kategori'),
                TextColumn::make('title')->sortable()->searchable()->label('Judul'),
                TextColumn::make('slug')->sortable()->searchable()->label('Slug'),
                TextColumn::make('content')->limit(50)->wrap()->label('Isi Berita'),
                ToggleColumn::make('is_featured')->label('Featured'),


            ])
            ->filters([
                SelectFilter::make('author_id')->relationship('author', 'name')->label('Select Author'),
                SelectFilter::make('news_category_id')->relationship('newsCategory', 'title')->label('Select Category'),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),

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
            'index' => ListNews::route('/'),
            'create' => CreateNews::route('/create'),
            'edit' => EditNews::route('/{record}/edit'),
        ];
    }
}
