<?php

namespace App\Filament\Business\Resources;

use App\Models\Coupon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Filament\Business\Resources\CouponResource\Pages;

class CouponResource extends Resource
{
    protected static ?string $model = Coupon::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Growth';

    protected static ?string $modelLabel = 'Coupon';

    protected static ?string $pluralModelLabel = 'Coupons';

    public static function canViewAny(): bool
    {
        $user = auth()->user();
        if (! $user?->business_id) {
            return $user?->is_platform_admin ?? false;
        }
        return app(\App\Core\Services\ModuleRegistry::class)->isEnabled((int) $user->business_id, 'coupons');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
                Forms\Components\TextInput::make('code')->maxLength(255),
                Forms\Components\TextInput::make('type')->maxLength(255),
                Forms\Components\TextInput::make('value')->numeric(),
                Forms\Components\Toggle::make('is_active'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('type')->badge()->sortable(),
                Tables\Columns\TextColumn::make('value')->searchable()->sortable(),
                Tables\Columns\IconColumn::make('is_active')->boolean(),
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
            'index' => Pages\ListCoupons::route('/'),
            'create' => Pages\CreateCoupon::route('/create'),
            'edit' => Pages\EditCoupon::route('/{record}/edit'),
        ];
    }
}
