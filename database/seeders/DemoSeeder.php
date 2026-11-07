<?php

namespace Database\Seeders;

use App\Core\Services\ModuleRegistry;
use App\Models\Account;
use App\Models\Branch;
use App\Models\Business;
use App\Models\Campaign;
use App\Models\CashDrawer;
use App\Models\Category;
use App\Models\Channel;
use App\Models\Conversation;
use App\Models\Coupon;
use App\Models\CourierAccount;
use App\Models\Customer;
use App\Models\DocumentTemplate;
use App\Models\Employee;
use App\Models\Lead;
use App\Models\Message;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Plan;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Review;
use App\Models\Shipment;
use App\Models\StockItem;
use App\Models\Supplier;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Warehouse;
use App\Services\AccountingService;
use App\Support\TenantContext;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        $registry = app(ModuleRegistry::class);
        $pro = Plan::query()->where('code', 'pro')->first();

        $demos = [
            [
                'name' => 'Demo Fashion BD',
                'slug' => 'demo-fashion',
                'type' => 'fashion',
                'preset' => 'fashion',
                'email_domain' => 'demo-fashion.test',
                'branches' => 1,
                'products' => [
                    ['Classic Blue Shirt', 129900],
                    ['Summer Linen Dress', 189900],
                    ['Denim Jacket', 249900],
                    ['Cotton Kurti', 99900],
                    ['Kids Polo', 69900],
                ],
            ],
            [
                'name' => 'Demo Gadget Hub',
                'slug' => 'demo-gadgets',
                'type' => 'gadgets',
                'preset' => 'gadgets',
                'email_domain' => 'demo-gadgets.test',
                'branches' => 3,
                'products' => [
                    ['Wireless Earbuds', 159900],
                    ['USB-C Hub', 89900],
                    ['Smart Watch Lite', 329900],
                    ['Power Bank 20k', 149900],
                    ['Bluetooth Speaker', 119900],
                ],
            ],
            [
                'name' => 'Demo Home Kitchen',
                'slug' => 'demo-food',
                'type' => 'food',
                'preset' => 'food',
                'email_domain' => 'demo-food.test',
                'branches' => 1,
                'products' => [
                    ['Mustard Oil 1L', 39000],
                    ['Spice Box Set', 79000],
                    ['Nonstick Pan', 149900],
                    ['Masala Mix', 25000],
                    ['Rice Cooker', 289900],
                ],
            ],
        ];

        foreach ($demos as $demo) {
            $this->seedBusiness($demo, $pro, $registry);
        }
    }

    protected function seedBusiness(array $demo, ?Plan $plan, ModuleRegistry $registry): void
    {
        $business = Business::query()->updateOrCreate(
            ['slug' => $demo['slug']],
            [
                'ulid' => (string) Str::ulid(),
                'name' => $demo['name'],
                'type' => $demo['type'],
                'email' => 'hello@'.$demo['email_domain'],
                'phone' => '01700000000',
                'currency' => 'BDT',
                'timezone' => 'Asia/Dhaka',
                'locale' => 'bn',
                'is_demo' => true,
                'is_active' => true,
                'plan_id' => $plan?->id,
                'settings' => ['demo' => true],
            ]
        );

        TenantContext::set($business->id);

        $registry->applyPreset($business->id, $demo['preset']);
        if ($demo['branches'] > 1) {
            $registry->enable($business->id, 'multi_branch');
        }

        $branches = [];
        for ($i = 1; $i <= $demo['branches']; $i++) {
            $branches[] = Branch::query()->updateOrCreate(
                ['business_id' => $business->id, 'code' => 'BR'.$i],
                [
                    'name' => $i === 1 ? 'Main Branch' : "Branch {$i}",
                    'phone' => '0180000000'.$i,
                    'address' => "Demo address {$i}, Dhaka",
                    'is_default' => $i === 1,
                    'is_active' => true,
                ]
            );
        }
        $main = $branches[0];
        TenantContext::set($business->id, $main->id);

        $users = $this->seedUsers($business, $main, $demo['email_domain']);

        // Idempotent: skip transactional demo rows if this business was already seeded.
        if (Customer::query()->withoutGlobalScopes()->where('business_id', $business->id)->exists()) {
            TenantContext::clear();

            return;
        }

        $category = Category::query()->updateOrCreate(
            ['business_id' => $business->id, 'slug' => $demo['type']],
            ['name' => ucfirst($demo['type'])]
        );

        $warehouse = Warehouse::query()->updateOrCreate(
            ['business_id' => $business->id, 'code' => 'MAIN'],
            ['branch_id' => $main->id, 'name' => 'Main Warehouse', 'is_default' => true]
        );

        $products = [];
        foreach ($demo['products'] as $idx => [$name, $price]) {
            $product = Product::query()->updateOrCreate(
                ['business_id' => $business->id, 'sku' => strtoupper($demo['type']).'-'.($idx + 1)],
                [
                    'ulid' => (string) Str::ulid(),
                    'category_id' => $category->id,
                    'name' => $name,
                    'slug' => Str::slug($name).'-'.$business->id,
                    'description' => $name.' demo product',
                    'price' => $price,
                    'cost_price' => (int) round($price * 0.6),
                    'track_inventory' => true,
                    'is_active' => true,
                ]
            );
            $variant = ProductVariant::query()->updateOrCreate(
                ['business_id' => $business->id, 'product_id' => $product->id, 'sku' => $product->sku.'-DEF'],
                ['name' => 'Default', 'price' => $price, 'cost_price' => $product->cost_price, 'attributes' => ['size' => 'M'], 'is_active' => true]
            );
            StockItem::query()->updateOrCreate(
                ['warehouse_id' => $warehouse->id, 'product_id' => $product->id, 'product_variant_id' => $variant->id],
                ['business_id' => $business->id, 'quantity' => 100, 'reserved' => 0, 'reorder_point' => 10]
            );
            $products[] = $product;
        }

        // Extra catalog volume for realism (lite vs prompt's 100-300)
        for ($i = count($products); $i < 25; $i++) {
            $p = Product::query()->create([
                'ulid' => (string) Str::ulid(),
                'business_id' => $business->id,
                'category_id' => $category->id,
                'name' => ucfirst($demo['type']).' Item '.($i + 1),
                'slug' => $demo['type'].'-item-'.($i + 1).'-'.$business->id,
                'sku' => strtoupper($demo['type']).'-X'.($i + 1),
                'price' => random_int(50000, 300000),
                'cost_price' => random_int(20000, 150000),
                'is_active' => true,
            ]);
            $products[] = $p;
            StockItem::query()->create([
                'business_id' => $business->id,
                'warehouse_id' => $warehouse->id,
                'product_id' => $p->id,
                'quantity' => random_int(20, 200),
                'reserved' => 0,
                'reorder_point' => 5,
            ]);
        }

        $channels = [];
        foreach (['whatsapp', 'facebook', 'instagram'] as $type) {
            $channels[] = Channel::query()->updateOrCreate(
                ['business_id' => $business->id, 'type' => $type],
                [
                    'branch_id' => $main->id,
                    'name' => ucfirst($type).' Demo',
                    'external_id' => 'mock_'.$type.'_'.$business->id,
                    'status' => 'connected',
                    'is_mock' => true,
                    'access_token' => 'mock-token-'.$type,
                ]
            );
        }

        CourierAccount::query()->updateOrCreate(
            ['business_id' => $business->id, 'provider' => 'steadfast'],
            ['name' => 'Steadfast Mock', 'is_mock' => true, 'is_active' => true, 'credentials' => ['api_key' => 'demo']]
        );
        CourierAccount::query()->updateOrCreate(
            ['business_id' => $business->id, 'provider' => 'pathao'],
            ['name' => 'Pathao Mock', 'is_mock' => true, 'is_active' => true, 'credentials' => ['api_key' => 'demo']]
        );

        Supplier::query()->updateOrCreate(
            ['business_id' => $business->id, 'name' => 'Demo Supplier'],
            ['phone' => '01911111111', 'email' => 'supplier@'.$demo['email_domain']]
        );

        CashDrawer::query()->updateOrCreate(
            ['business_id' => $business->id, 'name' => 'Front Desk'],
            ['branch_id' => $main->id, 'opening_balance' => 500000, 'current_balance' => 500000, 'is_open' => true]
        );

        app(AccountingService::class)->ensureChart($business->id);

        DocumentTemplate::query()->updateOrCreate(
            ['business_id' => $business->id, 'key' => 'invoice'],
            [
                'name' => 'Invoice BN',
                'type' => 'invoice',
                'locale' => 'bn',
                'body_html' => '<h1>ইনভয়েস {{number}}</h1>',
                'is_default' => true,
            ]
        );

        Coupon::query()->updateOrCreate(
            ['business_id' => $business->id, 'code' => 'DEMO10'],
            ['type' => 'percent', 'value' => 10, 'min_order' => 100000, 'is_active' => true]
        );

        Campaign::query()->updateOrCreate(
            ['business_id' => $business->id, 'name' => 'Welcome broadcast'],
            ['channel' => 'whatsapp', 'status' => 'draft', 'content' => 'Thanks for chatting with us!', 'recipients_count' => 0]
        );

        for ($i = 1; $i <= 40; $i++) {
            $customer = Customer::query()->create([
                'ulid' => (string) Str::ulid(),
                'business_id' => $business->id,
                'branch_id' => $main->id,
                'name' => 'Customer '.$i,
                'phone' => '0171'.str_pad((string) ($business->id * 100 + $i), 7, '0', STR_PAD_LEFT),
                'preferred_channel' => $channels[$i % 3]->type,
                'language' => $i % 2 ? 'bn' : 'en',
                'lifetime_value' => random_int(0, 500000),
            ]);

            if ($i <= 8) {
                $conversation = Conversation::query()->create([
                    'ulid' => (string) Str::ulid(),
                    'business_id' => $business->id,
                    'branch_id' => $main->id,
                    'channel_id' => $channels[$i % 3]->id,
                    'customer_id' => $customer->id,
                    'assigned_to' => $users['agent']->id,
                    'external_thread_id' => 'thread_'.$business->id.'_'.$i,
                    'status' => 'open',
                    'subject' => 'Chat with '.$customer->name,
                    'unread_count' => 1,
                    'last_message_at' => now()->subHours($i),
                ]);
                Message::query()->create([
                    'business_id' => $business->id,
                    'conversation_id' => $conversation->id,
                    'direction' => 'inbound',
                    'sender_type' => 'customer',
                    'external_id' => 'msg_'.$business->id.'_'.$i,
                    'body' => $i % 2
                        ? "আমি {$customer->name}\n{$customer->phone}\norder চাই: {$products[0]->name} 1 pcs size L\nঠিকানা: ঢাকা"
                        : "Hi, I want {$products[0]->name}, phone {$customer->phone}, deliver to Dhaka",
                    'sent_at' => now()->subHours($i),
                ]);
            }

            if ($i <= 30) {
                $product = $products[$i % count($products)];
                $qty = random_int(1, 3);
                $statuses = ['draft', 'pending', 'confirmed', 'processing', 'packed', 'shipped', 'delivered', 'cancelled'];
                $status = $statuses[$i % count($statuses)];
                $order = Order::query()->create([
                    'ulid' => (string) Str::ulid(),
                    'business_id' => $business->id,
                    'branch_id' => $main->id,
                    'customer_id' => $customer->id,
                    'assigned_to' => $users['agent']->id,
                    'number' => 'ORD-'.str_pad((string) $i, 5, '0', STR_PAD_LEFT),
                    'status' => $status,
                    'source' => $channels[$i % 3]->type,
                    'payment_status' => $status === 'delivered' ? 'paid' : 'unpaid',
                    'payment_method' => 'cod',
                    'subtotal' => $product->price * $qty,
                    'discount_total' => 0,
                    'tax_total' => 0,
                    'delivery_charge' => 8000,
                    'grand_total' => $product->price * $qty + 8000,
                    'paid_total' => $status === 'delivered' ? $product->price * $qty + 8000 : 0,
                    'due_total' => $status === 'delivered' ? 0 : $product->price * $qty + 8000,
                    'currency' => 'BDT',
                    'shipping_name' => $customer->name,
                    'shipping_phone' => $customer->phone,
                    'shipping_address' => 'Dhaka, Bangladesh',
                    'public_token' => Str::random(40),
                    'confirmed_at' => in_array($status, ['confirmed', 'processing', 'packed', 'shipped', 'delivered'], true) ? now()->subDays($i) : null,
                    'delivered_at' => $status === 'delivered' ? now()->subDays(max(1, $i - 5)) : null,
                    'created_at' => now()->subDays(random_int(1, 120)),
                ]);
                OrderItem::query()->create([
                    'business_id' => $business->id,
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'name' => $product->name,
                    'sku' => $product->sku,
                    'quantity' => $qty,
                    'unit_price' => $product->price,
                    'discount' => 0,
                    'tax' => 0,
                    'line_total' => $product->price * $qty,
                ]);

                if (in_array($status, ['shipped', 'delivered'], true)) {
                    Shipment::query()->create([
                        'business_id' => $business->id,
                        'order_id' => $order->id,
                        'rider_id' => $users['rider']->id,
                        'tracking_number' => 'TRK'.$business->id.$i,
                        'status' => $status === 'delivered' ? 'delivered' : 'in_transit',
                        'cod_amount' => $order->due_total,
                        'courier_charge' => 7000,
                        'shipped_at' => now()->subDays($i),
                        'delivered_at' => $status === 'delivered' ? now()->subDays(max(1, $i - 3)) : null,
                    ]);
                }

                if ($status === 'delivered') {
                    Payment::query()->create([
                        'business_id' => $business->id,
                        'order_id' => $order->id,
                        'customer_id' => $customer->id,
                        'method' => 'cod',
                        'gateway' => 'cod',
                        'status' => 'paid',
                        'amount' => $order->grand_total,
                        'currency' => 'BDT',
                        'paid_at' => now()->subDays(max(1, $i - 3)),
                    ]);
                    Review::query()->create([
                        'business_id' => $business->id,
                        'order_id' => $order->id,
                        'customer_id' => $customer->id,
                        'product_id' => $product->id,
                        'rating' => random_int(3, 5),
                        'body' => 'ভালো প্রোডাক্ট / Great product',
                        'is_published' => true,
                    ]);
                }
            }

            if ($i <= 10) {
                Lead::query()->create([
                    'business_id' => $business->id,
                    'customer_id' => $customer->id,
                    'assigned_to' => $users['agent']->id,
                    'name' => $customer->name,
                    'phone' => $customer->phone,
                    'stage' => ['new', 'contacted', 'qualified', 'won'][$i % 4],
                    'value' => random_int(50000, 400000),
                    'source' => 'facebook',
                ]);
            }

            if ($i <= 5) {
                Ticket::query()->create([
                    'business_id' => $business->id,
                    'customer_id' => $customer->id,
                    'assigned_to' => $users['support']->id,
                    'number' => 'TKT-'.str_pad((string) $i, 4, '0', STR_PAD_LEFT),
                    'subject' => 'Delivery question '.$i,
                    'status' => 'open',
                    'priority' => 'normal',
                    'body' => 'Customer asked about delivery time.',
                ]);
            }
        }

        Employee::query()->updateOrCreate(
            ['business_id' => $business->id, 'employee_code' => 'EMP001'],
            [
                'branch_id' => $main->id,
                'user_id' => $users['agent']->id,
                'name' => $users['agent']->name,
                'phone' => $users['agent']->phone,
                'designation' => 'Sales Agent',
                'department' => 'Sales',
                'joined_at' => now()->subMonths(6),
                'salary' => 2500000,
                'is_active' => true,
            ]
        );

        TenantContext::clear();
    }

    protected function seedUsers(Business $business, Branch $branch, string $domain): array
    {
        $defs = [
            'owner' => ['Owner', 'owner'],
            'agent' => ['Agent', 'agent'],
            'rider' => ['Rider', 'rider'],
            'manager' => ['Manager', 'manager'],
            'accountant' => ['Accountant', 'accountant'],
            'hr' => ['HR Manager', 'hr'],
            'marketing' => ['Marketing', 'marketing'],
            'support' => ['Support', 'support'],
            'warehouse' => ['Warehouse', 'warehouse'],
        ];

        $users = [];
        foreach ($defs as $key => [$label, $role]) {
            $user = User::query()->updateOrCreate(
                ['email' => "{$key}@{$domain}"],
                [
                    'business_id' => $business->id,
                    'branch_id' => $branch->id,
                    'name' => "{$label} {$business->name}",
                    'phone' => '016'.random_int(10000000, 99999999),
                    'password' => Hash::make('password'),
                    'locale' => 'en',
                    'is_platform_admin' => false,
                    'is_demo' => true,
                    'panel_role' => $role,
                ]
            );
            $user->syncRoles([$role === 'owner' ? 'owner' : $role]);
            $users[$key] = $user;
        }

        return $users;
    }
}
