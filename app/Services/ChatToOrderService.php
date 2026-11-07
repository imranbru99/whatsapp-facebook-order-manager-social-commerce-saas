<?php

namespace App\Services;

use App\Models\Conversation;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatusHistory;
use App\Models\Product;
use Illuminate\Support\Str;

class ChatToOrderService
{
    public function __construct(
        protected AiOrderExtractionService $extraction,
        protected OrderTotalCalculator $totals,
    ) {}

    public function createDraftFromConversation(Conversation $conversation, ?string $overrideText = null): Order
    {
        $conversation->loadMissing(['messages', 'customer', 'channel']);
        $text = $overrideText ?: $conversation->messages->sortBy('sent_at')->pluck('body')->filter()->implode("\n");

        $extraction = $this->extraction->extract($text, (int) $conversation->business_id, $conversation);
        $data = $extraction->extracted ?? [];

        $customer = $conversation->customer;
        if (! $customer) {
            $customer = Customer::query()->create([
                'ulid' => (string) Str::ulid(),
                'business_id' => $conversation->business_id,
                'branch_id' => $conversation->branch_id,
                'name' => $data['customer_name'] ?? 'Chat Customer',
                'phone' => $data['phone'] ?? null,
                'preferred_channel' => $conversation->channel?->type,
                'language' => 'bn',
            ]);
            $conversation->update(['customer_id' => $customer->id]);
        }

        $order = Order::query()->create([
            'ulid' => (string) Str::ulid(),
            'business_id' => $conversation->business_id,
            'branch_id' => $conversation->branch_id,
            'customer_id' => $customer->id,
            'conversation_id' => $conversation->id,
            'number' => $this->nextNumber((int) $conversation->business_id),
            'status' => 'draft',
            'source' => $conversation->channel?->type ?? 'chat',
            'payment_method' => 'cod',
            'shipping_name' => $data['customer_name'] ?? $customer->name,
            'shipping_phone' => $data['phone'] ?? $customer->phone,
            'shipping_address' => $data['address'] ?? null,
            'public_token' => Str::random(40),
            'meta' => ['ai_extraction_id' => $extraction->id, 'confidence' => $extraction->confidence],
            'delivery_charge' => 0,
        ]);

        foreach ($data['products'] ?? [] as $line) {
            $product = Product::query()
                ->withoutGlobalScopes()
                ->where('business_id', $conversation->business_id)
                ->where('name', 'like', '%'.($line['name'] ?? '').'%')
                ->first();

            $qty = max(1, (int) ($line['quantity'] ?? 1));
            $unit = (int) ($line['price'] ?? $product?->price ?? 129900);

            OrderItem::query()->create([
                'business_id' => $conversation->business_id,
                'order_id' => $order->id,
                'product_id' => $product?->id,
                'name' => $line['name'] ?? $product?->name ?? 'Item',
                'sku' => $product?->sku,
                'quantity' => $qty,
                'unit_price' => $unit,
                'discount' => 0,
                'tax' => 0,
                'line_total' => $unit * $qty,
                'meta' => ['size' => $line['size'] ?? null, 'color' => $line['color'] ?? null],
            ]);
        }

        OrderStatusHistory::query()->create([
            'business_id' => $conversation->business_id,
            'order_id' => $order->id,
            'from_status' => null,
            'to_status' => 'draft',
            'notes' => 'Created from chat via AI extraction',
        ]);

        $extraction->update(['order_id' => $order->id]);

        return $this->totals->recalculate($order);
    }

    protected function nextNumber(int $businessId): string
    {
        $count = Order::query()->withoutGlobalScopes()->where('business_id', $businessId)->count() + 1;

        return 'ORD-'.str_pad((string) $count, 5, '0', STR_PAD_LEFT);
    }
}
