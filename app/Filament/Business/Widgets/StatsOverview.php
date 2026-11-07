<?php

namespace App\Filament\Business\Widgets;

use App\Models\Conversation;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Orders', Order::query()->count()),
            Stat::make('Open conversations', Conversation::query()->where('status', 'open')->count()),
            Stat::make('Customers', Customer::query()->count()),
            Stat::make('Products', Product::query()->count()),
        ];
    }
}
