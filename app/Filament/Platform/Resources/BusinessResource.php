<?php

namespace App\Filament\Platform\Resources;

use App\Models\Business;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Filament\Platform\Resources\BusinessResource\Pages;

class BusinessResource extends Resource
{
    protected static ?string $model = Business::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Tenants';

    protected static ?string $modelLabel = 'Business';

    protected static ?string $pluralModelLabel = 'Businesses';

    public static function form(Form $form): Form
    {
        return $form->schema([
                Forms\Components\TextInput::make('name')->maxLength(255),
                Forms\Components\TextInput::make('type')->maxLength(255),
                Forms\Components\TextInput::make('email')->maxLength(255),
                Forms\Components\TextInput::make('phone')->maxLength(255),
                Forms\Components\Toggle::make('is_active'),
                Forms\Components\Toggle::make('is_demo'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('type')->badge()->sortable(),
                Tables\Columns\IconColumn::make('is_active')->boolean(),
                Tables\Columns\IconColumn::make('is_demo')->boolean(),
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
            'index' => Pages\ListBusinesses::route('/'),
            'create' => Pages\CreateBusiness::route('/create'),
            'edit' => Pages\EditBusiness::route('/{record}/edit'),
        ];
    }
}
