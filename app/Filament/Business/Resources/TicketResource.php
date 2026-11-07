<?php

namespace App\Filament\Business\Resources;

use App\Models\Ticket;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Filament\Business\Resources\TicketResource\Pages;

class TicketResource extends Resource
{
    protected static ?string $model = Ticket::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Support';

    protected static ?string $modelLabel = 'Ticket';

    protected static ?string $pluralModelLabel = 'Tickets';

    public static function canViewAny(): bool
    {
        $user = auth()->user();
        if (! $user?->business_id) {
            return $user?->is_platform_admin ?? false;
        }
        return app(\App\Core\Services\ModuleRegistry::class)->isEnabled((int) $user->business_id, 'tickets');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
                Forms\Components\TextInput::make('number')->maxLength(255),
                Forms\Components\TextInput::make('subject')->maxLength(255),
                Forms\Components\TextInput::make('status')->maxLength(255),
                Forms\Components\TextInput::make('priority')->maxLength(255),
                Forms\Components\Textarea::make('body')->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('number')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('subject')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('status')->badge()->sortable(),
                Tables\Columns\TextColumn::make('priority')->badge()->sortable(),
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
            'index' => Pages\ListTickets::route('/'),
            'create' => Pages\CreateTicket::route('/create'),
            'edit' => Pages\EditTicket::route('/{record}/edit'),
        ];
    }
}
