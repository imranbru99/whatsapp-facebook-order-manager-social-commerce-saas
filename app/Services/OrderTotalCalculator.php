<?php

namespace App\Services;

use App\Models\Order;

class OrderTotalCalculator
{
    /**
     * Recalculate monetary totals for an order from its line items.
     *
     * Money is stored as integer minor units (e.g. paisa).
     */
    public function recalculate(Order $order): Order
    {
        $order->loadMissing('items');

        $subtotal = 0;
        $discount = 0;
        $tax = 0;

        foreach ($order->items as $item) {
            $line = max(0, ((int) $item->unit_price * (int) $item->quantity) - (int) $item->discount + (int) $item->tax);
            $item->line_total = $line;
            $item->save();

            $subtotal += (int) $item->unit_price * (int) $item->quantity;
            $discount += (int) $item->discount;
            $tax += (int) $item->tax;
        }

        $order->subtotal = $subtotal;
        $order->discount_total = $discount + (int) ($order->meta['order_discount'] ?? 0);
        $order->tax_total = $tax;
        $order->grand_total = max(
            0,
            $subtotal - $order->discount_total + $tax + (int) $order->delivery_charge
        );
        $order->due_total = max(0, $order->grand_total - (int) $order->paid_total);
        $order->save();

        return $order->fresh('items');
    }
}
