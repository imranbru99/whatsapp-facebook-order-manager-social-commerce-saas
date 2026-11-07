<?php

namespace App\Filament\Business\Resources;

use App\Models\Channel;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Filament\Business\Resources\ChannelResource\Pages;

class ChannelResource extends Resource
{
    protected static ?string $model = Channel::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Inbox';

    protected static ?string $modelLabel = 'Channel';

    protected static ?string $pluralModelLabel = 'Channels';

    public static function canViewAny(): bool
    {
        $user = auth()->user();
        if (! $user?->business_id) {
            return $user?->is_platform_admin ?? false;
        }
        return app(\App\Core\Services\ModuleRegistry::class)->isEnabled((int) $user->business_id, 'channels');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
                Forms\Components\TextInput::make('name')->maxLength(255),
                Forms\Components\TextInput::make('type')->maxLength(255),
                Forms\Components\TextInput::make('external_id')->maxLength(255),
                Forms\Components\TextInput::make('status')->maxLength(255),
                Forms\Components\Toggle::make('is_mock'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('type')->badge()->sortable(),
                Tables\Columns\TextColumn::make('status')->badge()->sortable(),
                Tables\Columns\IconColumn::make('is_mock')->boolean(),
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
            'index' => Pages\ListChannels::route('/'),
            'create' => Pages\CreateChannel::route('/create'),
            'edit' => Pages\EditChannel::route('/{record}/edit'),
        ];
    }
}
