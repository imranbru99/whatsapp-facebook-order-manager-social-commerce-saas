<?php

namespace App\Services\Couriers;

use App\Core\Contracts\CourierAdapterInterface;
use App\Models\Order;
use App\Models\Shipment;
use Illuminate\Support\Str;

class MockSteadfastAdapter implements CourierAdapterInterface
{
    public function provider(): string
    {
        return 'steadfast';
    }

    public function createParcel(Order $order, Shipment $shipment): array
    {
        return [
            'tracking_number' => 'SF'.strtoupper(Str::random(10)),
            'status' => 'pending',
            'charge' => max(60_00, (int) round($order->grand_total * 0.02)),
            'meta' => ['provider' => 'steadfast', 'mock' => true],
        ];
    }

    public function track(string $trackingNumber): array
    {
        return [
            'status' => 'in_transit',
            'events' => [
                ['status' => 'picked', 'description' => 'Parcel picked up (mock)'],
                ['status' => 'in_transit', 'description' => 'In transit to destination hub (mock)'],
            ],
        ];
    }
}
