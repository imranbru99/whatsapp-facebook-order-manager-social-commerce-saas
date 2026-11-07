<?php

namespace App\Core\Contracts;

use App\Models\Order;
use App\Models\Shipment;

interface CourierAdapterInterface
{
    public function provider(): string;

    /**
     * @return array{tracking_number: string, status: string, charge: int, meta: array}
     */
    public function createParcel(Order $order, Shipment $shipment): array;

    /**
     * @return array{status: string, events: list<array{status: string, description: string}>}
     */
    public function track(string $trackingNumber): array;
}
