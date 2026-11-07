<?php

namespace App\Filament\Platform\Resources\BusinessResource\Pages;

use App\Filament\Platform\Resources\BusinessResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBusinesses extends ListRecords
{
    protected static string $resource = BusinessResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
