<?php

namespace App\Filament\Agent\Resources;

use App\Models\Conversation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Filament\Agent\Resources\ConversationResource\Pages;

class ConversationResource extends Resource
{
    protected static ?string $model = Conversation::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Inbox';

    protected static ?string $modelLabel = 'Conversation';

    protected static ?string $pluralModelLabel = 'Conversations';

    public static function canViewAny(): bool
    {
        $user = auth()->user();
        if (! $user?->business_id) {
            return $user?->is_platform_admin ?? false;
        }
        return app(\App\Core\Services\ModuleRegistry::class)->isEnabled((int) $user->business_id, 'inbox');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
                Forms\Components\TextInput::make('subject')->maxLength(255),
                Forms\Components\TextInput::make('status')->maxLength(255),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('subject')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('status')->badge()->sortable(),
                Tables\Columns\TextColumn::make('unread_count')->searchable()->sortable(),
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
            'index' => Pages\ListConversations::route('/'),
            'create' => Pages\CreateConversation::route('/create'),
            'edit' => Pages\EditConversation::route('/{record}/edit'),
        ];
    }
}
