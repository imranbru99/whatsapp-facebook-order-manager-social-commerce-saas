<?php

namespace App\Http\Controllers\Web;

use App\Core\Services\ModuleRegistry;
use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Support\TenantContext;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class StorefrontController extends Controller
{
    public function show(Business $business)
    {
        TenantContext::set($business->id);
        abort_unless(app(ModuleRegistry::class)->isEnabled($business->id, 'storefront'), 404);

        $products = Product::query()->where('is_active', true)->latest()->take(24)->get();

        return view('marketing.storefront', compact('business', 'products'));
    }

    public function checkout(Request $request, Business $business)
    {
        TenantContext::set($business->id);
        abort_unless(app(ModuleRegistry::class)->isEnabled($business->id, 'storefront'), 404);

        $data = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'name' => ['required', 'string', 'max:120'],
            'phone' => ['required', 'string', 'max:30'],
            'address' => ['required', 'string', 'max:500'],
            'quantity' => ['nullable', 'integer', 'min:1', 'max:20'],
        ]);

        $product = Product::query()->findOrFail($data['product_id']);
        $qty = (int) ($data['quantity'] ?? 1);

        $customer = Customer::query()->firstOrCreate(
            ['business_id' => $business->id, 'phone' => $data['phone']],
            ['ulid' => (string) Str::ulid(), 'name' => $data['name'], 'language' => 'bn']
        );

        $order = Order::query()->create([
            'ulid' => (string) Str::ulid(),
            'business_id' => $business->id,
            'customer_id' => $customer->id,
            'number' => 'WEB-'.strtoupper(Str::random(6)),
            'status' => 'pending',
            'source' => 'storefront',
            'payment_method' => 'cod',
            'subtotal' => $product->price * $qty,
            'delivery_charge' => 8000,
            'grand_total' => $product->price * $qty + 8000,
            'due_total' => $product->price * $qty + 8000,
            'shipping_name' => $data['name'],
            'shipping_phone' => $data['phone'],
            'shipping_address' => $data['address'],
            'public_token' => Str::random(40),
        ]);

        OrderItem::query()->create([
            'business_id' => $business->id,
            'order_id' => $order->id,
            'product_id' => $product->id,
            'name' => $product->name,
            'sku' => $product->sku,
            'quantity' => $qty,
            'unit_price' => $product->price,
            'line_total' => $product->price * $qty,
        ]);

        return redirect()->route('storefront.show', $business)
            ->with('status', "Order {$order->number} placed. Pay COD on delivery.");
    }
}
