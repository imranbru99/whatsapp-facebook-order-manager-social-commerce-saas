<?php

namespace App\Filament\Business\Resources\WarehouseResource\Pages;

use App\Filament\Business\Resources\WarehouseResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWarehouses extends ListRecords
{
    protected static string $resource = WarehouseResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
