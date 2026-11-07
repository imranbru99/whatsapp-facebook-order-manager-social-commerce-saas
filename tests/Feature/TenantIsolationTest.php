<?php

namespace Tests\Feature;

use App\Models\Business;
use App\Models\Order;
use App\Models\User;
use App\Support\TenantContext;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class TenantIsolationTest extends TestCase
{
    use RefreshDatabase;

    public function test_orders_are_scoped_to_current_business(): void
    {
        $a = Business::query()->create([
            'ulid' => (string) Str::ulid(),
            'name' => 'Shop A',
            'slug' => 'shop-a',
        ]);
        $b = Business::query()->create([
            'ulid' => (string) Str::ulid(),
            'name' => 'Shop B',
            'slug' => 'shop-b',
        ]);

        TenantContext::set($a->id);
        Order::query()->create([
            'ulid' => (string) Str::ulid(),
            'business_id' => $a->id,
            'number' => 'A-1',
            'public_token' => Str::random(20),
            'status' => 'draft',
            'grand_total' => 1000,
        ]);

        TenantContext::set($b->id);
        Order::query()->create([
            'ulid' => (string) Str::ulid(),
            'business_id' => $b->id,
            'number' => 'B-1',
            'public_token' => Str::random(20),
            'status' => 'draft',
            'grand_total' => 2000,
        ]);

        TenantContext::set($a->id);
        $this->assertSame(1, Order::query()->count());
        $this->assertSame('A-1', Order::query()->first()->number);

        TenantContext::set($b->id);
        $this->assertSame(1, Order::query()->count());
        $this->assertSame('B-1', Order::query()->first()->number);

        $userA = User::factory()->create(['business_id' => $a->id, 'panel_role' => 'owner']);
        $this->actingAs($userA);
        TenantContext::set($a->id);
        $foreign = Order::query()->withoutGlobalScopes()->where('business_id', $b->id)->first();
        $this->assertFalse($userA->can('view', $foreign));
    }
}
