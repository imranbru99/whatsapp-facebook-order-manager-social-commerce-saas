<?php

namespace App\Filament\Business\Resources\CashDrawerResource\Pages;

use App\Filament\Business\Resources\CashDrawerResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCashDrawer extends EditRecord
{
    protected static string $resource = CashDrawerResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
