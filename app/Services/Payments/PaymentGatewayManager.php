<?php

namespace App\Services\Payments;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Str;

class PaymentGatewayManager
{
    /**
     * COD + manual are fully implemented. Card/mobile gateways are stubs that
     * mark payments as pending without calling remote APIs.
     */
    public function charge(Order $order, string $method, array $payload = []): Payment
    {
        $status = match ($method) {
            'cod' => 'pending',
            'manual' => ! empty($payload['trx_id']) ? 'paid' : 'pending',
            'bkash', 'nagad', 'sslcommerz', 'stripe', 'paypal', 'aamarpay', 'shurjopay', 'razorpay' => 'pending',
            default => 'pending',
        };

        $payment = Payment::query()->create([
            'business_id' => $order->business_id,
            'order_id' => $order->id,
            'customer_id' => $order->customer_id,
            'method' => $method,
            'gateway' => $method,
            'status' => $status,
            'amount' => (int) ($payload['amount'] ?? $order->due_total),
            'currency' => $order->currency,
            'trx_id' => $payload['trx_id'] ?? ($method === 'cod' ? null : 'STUB-'.Str::upper(Str::random(8))),
            'meta' => array_merge($payload, ['stub' => ! in_array($method, ['cod', 'manual'], true)]),
            'paid_at' => $status === 'paid' ? now() : null,
        ]);

        if ($status === 'paid') {
            $order->paid_total = (int) $order->paid_total + (int) $payment->amount;
            $order->due_total = max(0, (int) $order->grand_total - (int) $order->paid_total);
            $order->payment_status = $order->due_total === 0 ? 'paid' : 'partial';
            $order->save();
        }

        return $payment;
    }
}
