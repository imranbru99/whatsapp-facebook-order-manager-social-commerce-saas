<?php

namespace App\Filament\Business\Resources\ConversationResource\Pages;

use App\Filament\Business\Resources\ConversationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListConversations extends ListRecords
{
    protected static string $resource = ConversationResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
