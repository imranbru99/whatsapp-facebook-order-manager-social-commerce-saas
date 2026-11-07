<?php

namespace App\Filament\Rider\Resources;

use App\Models\Shipment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Filament\Rider\Resources\ShipmentResource\Pages;

class ShipmentResource extends Resource
{
    protected static ?string $model = Shipment::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Deliveries';

    protected static ?string $modelLabel = 'Shipment';

    protected static ?string $pluralModelLabel = 'Shipments';

    public static function canViewAny(): bool
    {
        $user = auth()->user();
        if (! $user?->business_id) {
            return $user?->is_platform_admin ?? false;
        }
        return app(\App\Core\Services\ModuleRegistry::class)->isEnabled((int) $user->business_id, 'couriers');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
                Forms\Components\TextInput::make('tracking_number')->maxLength(255),
                Forms\Components\TextInput::make('status')->maxLength(255),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tracking_number')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('status')->badge()->sortable(),
                Tables\Columns\TextColumn::make('cod_amount')->money('bdt', divideBy: 100)->sortable(),
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
            'index' => Pages\ListShipments::route('/'),
            'create' => Pages\CreateShipment::route('/create'),
            'edit' => Pages\EditShipment::route('/{record}/edit'),
        ];
    }
}
