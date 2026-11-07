<?php

namespace App\Filament\Business\Resources;

use App\Models\Warehouse;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Filament\Business\Resources\WarehouseResource\Pages;

class WarehouseResource extends Resource
{
    protected static ?string $model = Warehouse::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Inventory';

    protected static ?string $modelLabel = 'Warehouse';

    protected static ?string $pluralModelLabel = 'Warehouses';

    public static function canViewAny(): bool
    {
        $user = auth()->user();
        if (! $user?->business_id) {
            return $user?->is_platform_admin ?? false;
        }
        return app(\App\Core\Services\ModuleRegistry::class)->isEnabled((int) $user->business_id, 'inventory');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
                Forms\Components\TextInput::make('name')->maxLength(255),
                Forms\Components\TextInput::make('code')->maxLength(255),
                Forms\Components\Toggle::make('is_default'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('code')->searchable()->sortable(),
                Tables\Columns\IconColumn::make('is_default')->boolean(),
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
            'index' => Pages\ListWarehouses::route('/'),
            'create' => Pages\CreateWarehouse::route('/create'),
            'edit' => Pages\EditWarehouse::route('/{record}/edit'),
        ];
    }
}
