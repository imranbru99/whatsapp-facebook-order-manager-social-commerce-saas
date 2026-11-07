<?php

namespace App\Filament\Business\Resources;

use App\Models\Payment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Filament\Business\Resources\PaymentResource\Pages;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Finance';

    protected static ?string $modelLabel = 'Payment';

    protected static ?string $pluralModelLabel = 'Payments';

    public static function canViewAny(): bool
    {
        $user = auth()->user();
        if (! $user?->business_id) {
            return $user?->is_platform_admin ?? false;
        }
        return app(\App\Core\Services\ModuleRegistry::class)->isEnabled((int) $user->business_id, 'payments');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
                Forms\Components\TextInput::make('method')->maxLength(255),
                Forms\Components\TextInput::make('status')->maxLength(255),
                Forms\Components\TextInput::make('amount')->numeric(),
                Forms\Components\TextInput::make('trx_id')->maxLength(255),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('method')->badge()->sortable(),
                Tables\Columns\TextColumn::make('status')->badge()->sortable(),
                Tables\Columns\TextColumn::make('amount')->money('bdt', divideBy: 100)->sortable(),
                Tables\Columns\TextColumn::make('trx_id')->searchable()->sortable(),
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
            'index' => Pages\ListPayments::route('/'),
            'create' => Pages\CreatePayment::route('/create'),
            'edit' => Pages\EditPayment::route('/{record}/edit'),
        ];
    }
}
