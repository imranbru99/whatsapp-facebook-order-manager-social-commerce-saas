<?php

namespace App\Filament\Business\Resources;

use App\Models\CashDrawer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Filament\Business\Resources\CashDrawerResource\Pages;

class CashDrawerResource extends Resource
{
    protected static ?string $model = CashDrawer::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Finance';

    protected static ?string $modelLabel = 'CashDrawer';

    protected static ?string $pluralModelLabel = 'CashDrawers';

    public static function canViewAny(): bool
    {
        $user = auth()->user();
        if (! $user?->business_id) {
            return $user?->is_platform_admin ?? false;
        }
        return app(\App\Core\Services\ModuleRegistry::class)->isEnabled((int) $user->business_id, 'cash');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
                Forms\Components\TextInput::make('name')->maxLength(255),
                Forms\Components\TextInput::make('opening_balance')->numeric(),
                Forms\Components\TextInput::make('current_balance')->numeric(),
                Forms\Components\Toggle::make('is_open'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('current_balance')->money('bdt', divideBy: 100)->sortable(),
                Tables\Columns\IconColumn::make('is_open')->boolean(),
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
            'index' => Pages\ListCashDrawers::route('/'),
            'create' => Pages\CreateCashDrawer::route('/create'),
            'edit' => Pages\EditCashDrawer::route('/{record}/edit'),
        ];
    }
}
