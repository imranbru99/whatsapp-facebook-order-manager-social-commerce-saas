<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Support\TenantContext;
use Illuminate\Http\Request;

class OrderApiController extends Controller
{
    public function index(Request $request)
    {
        TenantContext::set((int) $request->user()->business_id, $request->user()->branch_id);

        if (! app(\App\Core\Services\ModuleRegistry::class)->isEnabled((int) $request->user()->business_id, 'orders')) {
            return response()->json(['message' => 'Module disabled'], 403);
        }

        return Order::query()->with('items')->latest()->paginate(20);
    }

    public function show(Request $request, Order $order)
    {
        TenantContext::set((int) $request->user()->business_id, $request->user()->branch_id);
        abort_unless((int) $order->business_id === (int) $request->user()->business_id, 404);

        return $order->load('items', 'payments', 'shipments');
    }
}
