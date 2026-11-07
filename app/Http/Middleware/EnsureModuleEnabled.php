<?php

namespace App\Http\Middleware;

use App\Core\Services\ModuleRegistry;
use App\Support\TenantContext;
use Closure;
use Illuminate\Http\Request;

class EnsureModuleEnabled
{
    public function __construct(protected ModuleRegistry $modules) {}

    public function handle(Request $request, Closure $next, string $moduleKey)
    {
        $businessId = TenantContext::businessId() ?? $request->user()?->business_id;
        if (! $businessId || ! $this->modules->isEnabled((int) $businessId, $moduleKey)) {
            abort(403, "Module [{$moduleKey}] is disabled for this business.");
        }

        return $next($request);
    }
}
