<?php

namespace App\Filament\Business\Resources\EmployeeResource\Pages;

use App\Filament\Business\Resources\EmployeeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEmployee extends EditRecord
{
    protected static string $resource = EmployeeResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
