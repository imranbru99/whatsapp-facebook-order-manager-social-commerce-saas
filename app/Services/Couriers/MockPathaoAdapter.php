<?php

namespace App\Services\Couriers;

use App\Core\Contracts\CourierAdapterInterface;
use App\Models\Order;
use App\Models\Shipment;
use Illuminate\Support\Str;

class MockPathaoAdapter implements CourierAdapterInterface
{
    public function provider(): string
    {
        return 'pathao';
    }

    public function createParcel(Order $order, Shipment $shipment): array
    {
        return [
            'tracking_number' => 'PT'.strtoupper(Str::random(10)),
            'status' => 'pending',
            'charge' => max(70_00, (int) round($order->grand_total * 0.025)),
            'meta' => ['provider' => 'pathao', 'mock' => true],
        ];
    }

    public function track(string $trackingNumber): array
    {
        return [
            'status' => 'out_for_delivery',
            'events' => [
                ['status' => 'accepted', 'description' => 'Order accepted by Pathao (mock)'],
                ['status' => 'out_for_delivery', 'description' => 'Rider assigned (mock)'],
            ],
        ];
    }
}
