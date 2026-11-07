<?php

namespace App\Filament\Platform\Resources\BusinessResource\Pages;

use App\Filament\Platform\Resources\BusinessResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBusiness extends EditRecord
{
    protected static string $resource = BusinessResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
