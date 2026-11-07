<?php

namespace Tests\Feature;

use App\Models\Business;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\OrderTotalCalculator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class OrderTotalsTest extends TestCase
{
    use RefreshDatabase;

    public function test_recalculate_computes_grand_total_from_lines(): void
    {
        $business = Business::query()->create([
            'ulid' => (string) Str::ulid(),
            'name' => 'Totals Co',
            'slug' => 'totals-co',
        ]);

        $order = Order::query()->create([
            'ulid' => (string) Str::ulid(),
            'business_id' => $business->id,
            'number' => 'T-1',
            'public_token' => Str::random(20),
            'delivery_charge' => 8000,
            'paid_total' => 0,
        ]);

        OrderItem::query()->create([
            'business_id' => $business->id,
            'order_id' => $order->id,
            'name' => 'Shirt',
            'quantity' => 2,
            'unit_price' => 10000,
            'discount' => 500,
            'tax' => 0,
            'line_total' => 0,
        ]);
        OrderItem::query()->create([
            'business_id' => $business->id,
            'order_id' => $order->id,
            'name' => 'Belt',
            'quantity' => 1,
            'unit_price' => 3000,
            'discount' => 0,
            'tax' => 200,
            'line_total' => 0,
        ]);

        $order = app(OrderTotalCalculator::class)->recalculate($order->fresh('items'));

        // subtotal 20000+3000=23000; discount 500; tax 200; delivery 8000 => 30700
        $this->assertSame(23000, $order->subtotal);
        $this->assertSame(500, $order->discount_total);
        $this->assertSame(200, $order->tax_total);
        $this->assertSame(30700, $order->grand_total);
        $this->assertSame(30700, $order->due_total);
    }
}
