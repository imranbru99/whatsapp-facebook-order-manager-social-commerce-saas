<?php

namespace App\Filament\Platform\Resources;

use App\Models\CmsPage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Filament\Platform\Resources\CmsPageResource\Pages;

class CmsPageResource extends Resource
{
    protected static ?string $model = CmsPage::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'CMS';

    protected static ?string $modelLabel = 'CmsPage';

    protected static ?string $pluralModelLabel = 'CmsPages';

    public static function form(Form $form): Form
    {
        return $form->schema([
                Forms\Components\TextInput::make('title')->maxLength(255),
                Forms\Components\TextInput::make('slug')->maxLength(255),
                Forms\Components\TextInput::make('locale')->maxLength(255),
                Forms\Components\TextInput::make('meta_title')->maxLength(255),
                Forms\Components\Textarea::make('meta_description')->columnSpanFull(),
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
                Tables\Columns\TextColumn::make('locale')->badge()->sortable(),
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
            'index' => Pages\ListCmsPages::route('/'),
            'create' => Pages\CreateCmsPage::route('/create'),
            'edit' => Pages\EditCmsPage::route('/{record}/edit'),
        ];
    }
}
