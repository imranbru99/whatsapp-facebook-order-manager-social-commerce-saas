<?php

namespace App\Http\Middleware;

use App\Support\TenantContext;
use Closure;
use Illuminate\Http\Request;

class SetTenantContext
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        if ($user && $user->business_id) {
            TenantContext::set((int) $user->business_id, $user->branch_id ? (int) $user->branch_id : null);
        } else {
            TenantContext::clear();
        }

        return $next($request);
    }
}
