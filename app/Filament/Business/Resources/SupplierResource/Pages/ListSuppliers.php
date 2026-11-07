<?php

namespace App\Filament\Business\Resources\SupplierResource\Pages;

use App\Filament\Business\Resources\SupplierResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSuppliers extends ListRecords
{
    protected static string $resource = SupplierResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
