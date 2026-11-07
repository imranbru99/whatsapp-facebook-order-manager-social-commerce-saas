<?php

namespace App\Providers;

use App\Core\Services\ModuleRegistry;
use App\Models\Business;
use App\Models\Order;
use App\Policies\BusinessPolicy;
use App\Policies\OrderPolicy;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(ModuleRegistry::class);
    }

    public function boot(): void
    {
        Gate::policy(Order::class, OrderPolicy::class);
        Gate::policy(Business::class, BusinessPolicy::class);

        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(120)->by($request->user()?->id ?: $request->ip());
        });

        if (! $this->app->environment('production')) {
            \Illuminate\Database\Eloquent\Model::preventLazyLoading(false);
        }
    }
}
