<?php

namespace App\Filament\Business\Resources\CampaignResource\Pages;

use App\Filament\Business\Resources\CampaignResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCampaign extends EditRecord
{
    protected static string $resource = CampaignResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
