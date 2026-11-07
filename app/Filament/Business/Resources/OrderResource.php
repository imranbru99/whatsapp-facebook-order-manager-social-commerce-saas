<?php

namespace App\Filament\Business\Resources;

use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Filament\Business\Resources\OrderResource\Pages;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Orders';

    protected static ?string $modelLabel = 'Order';

    protected static ?string $pluralModelLabel = 'Orders';

    public static function canViewAny(): bool
    {
        $user = auth()->user();
        if (! $user?->business_id) {
            return $user?->is_platform_admin ?? false;
        }
        return app(\App\Core\Services\ModuleRegistry::class)->isEnabled((int) $user->business_id, 'orders');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
                Forms\Components\TextInput::make('number')->maxLength(255),
                Forms\Components\Select::make('status')->options(['draft'=>'Draft','pending'=>'Pending','confirmed'=>'Confirmed','processing'=>'Processing','packed'=>'Packed','shipped'=>'Shipped','delivered'=>'Delivered','cancelled'=>'Cancelled'])->required(),
                Forms\Components\TextInput::make('shipping_name')->maxLength(255),
                Forms\Components\TextInput::make('shipping_phone')->maxLength(255),
                Forms\Components\Textarea::make('shipping_address')->columnSpanFull(),
                Forms\Components\TextInput::make('delivery_charge')->numeric(),
                Forms\Components\Textarea::make('internal_notes')->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('number')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('status')->badge()->sortable(),
                Tables\Columns\TextColumn::make('grand_total')->money('bdt', divideBy: 100)->sortable(),
                Tables\Columns\TextColumn::make('payment_status')->badge()->sortable(),
                Tables\Columns\TextColumn::make('source')->searchable()->sortable(),
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
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
