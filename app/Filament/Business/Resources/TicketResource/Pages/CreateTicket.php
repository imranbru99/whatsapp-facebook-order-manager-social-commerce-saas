<?php

namespace App\Filament\Business\Resources\TicketResource\Pages;

use App\Filament\Business\Resources\TicketResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTicket extends CreateRecord
{
    protected static string $resource = TicketResource::class;
}
