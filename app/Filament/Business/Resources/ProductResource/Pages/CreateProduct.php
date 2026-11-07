<?php

namespace App\Filament\Business\Resources\ProductResource\Pages;

use App\Filament\Business\Resources\ProductResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;
}
