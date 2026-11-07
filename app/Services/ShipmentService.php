<?php

namespace App\Services;

use App\Models\CourierAccount;
use App\Models\Order;
use App\Models\Shipment;
use App\Models\ShipmentEvent;
use App\Services\Couriers\MockPathaoAdapter;
use App\Services\Couriers\MockSteadfastAdapter;
use App\Core\Contracts\CourierAdapterInterface;
use Illuminate\Support\Carbon;

class ShipmentService
{
    public function createForOrder(Order $order, CourierAccount $account): Shipment
    {
        $adapter = $this->adapterFor($account);
        $shipment = Shipment::query()->create([
            'business_id' => $order->business_id,
            'order_id' => $order->id,
            'courier_account_id' => $account->id,
            'status' => 'pending',
            'cod_amount' => $order->due_total,
        ]);

        $result = $adapter->createParcel($order, $shipment);
        $shipment->update([
            'tracking_number' => $result['tracking_number'],
            'status' => $result['status'],
            'courier_charge' => $result['charge'],
            'meta' => $result['meta'],
            'shipped_at' => now(),
        ]);

        ShipmentEvent::query()->create([
            'shipment_id' => $shipment->id,
            'status' => 'created',
            'description' => 'Shipment created via '.$adapter->provider().' mock',
            'happened_at' => Carbon::now(),
        ]);

        $order->update(['status' => 'shipped']);

        return $shipment->fresh('events');
    }

    public function syncTracking(Shipment $shipment): Shipment
    {
        $account = $shipment->courierAccount;
        if (! $account) {
            return $shipment;
        }

        $result = $this->adapterFor($account)->track((string) $shipment->tracking_number);
        $shipment->update(['status' => $result['status']]);

        foreach ($result['events'] as $event) {
            ShipmentEvent::query()->firstOrCreate(
                [
                    'shipment_id' => $shipment->id,
                    'status' => $event['status'],
                    'description' => $event['description'],
                ],
                ['happened_at' => now()]
            );
        }

        return $shipment->fresh('events');
    }

    protected function adapterFor(CourierAccount $account): CourierAdapterInterface
    {
        return match ($account->provider) {
            'pathao' => new MockPathaoAdapter,
            default => new MockSteadfastAdapter,
        };
    }
}
