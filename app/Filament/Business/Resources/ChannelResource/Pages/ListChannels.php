<?php

namespace App\Filament\Business\Resources\ChannelResource\Pages;

use App\Filament\Business\Resources\ChannelResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListChannels extends ListRecords
{
    protected static string $resource = ChannelResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
