<?php

namespace App\Filament\Platform\Resources;

use App\Models\BlogPost;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Filament\Platform\Resources\BlogPostResource\Pages;

class BlogPostResource extends Resource
{
    protected static ?string $model = BlogPost::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'CMS';

    protected static ?string $modelLabel = 'BlogPost';

    protected static ?string $pluralModelLabel = 'BlogPosts';

    public static function form(Form $form): Form
    {
        return $form->schema([
                Forms\Components\TextInput::make('title')->maxLength(255),
                Forms\Components\TextInput::make('slug')->maxLength(255),
                Forms\Components\Textarea::make('excerpt')->columnSpanFull(),
                Forms\Components\Textarea::make('body')->columnSpanFull(),
                Forms\Components\Toggle::make('is_published'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('slug')->searchable()->sortable(),
                Tables\Columns\IconColumn::make('is_published')->boolean(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBlogPosts::route('/'),
            'create' => Pages\CreateBlogPost::route('/create'),
            'edit' => Pages\EditBlogPost::route('/{record}/edit'),
        ];
    }
}
