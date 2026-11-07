<?php

namespace App\Core\Concerns;

use App\Models\Business;
use App\Support\TenantContext;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToBusiness
{
    public static function bootBelongsToBusiness(): void
    {
        static::addGlobalScope('business', function (Builder $builder): void {
            $businessId = TenantContext::businessId();
            if ($businessId) {
                $builder->where($builder->getModel()->getTable().'.business_id', $businessId);
            }
        });

        static::creating(function (Model $model): void {
            if (! $model->getAttribute('business_id') && TenantContext::businessId()) {
                $model->setAttribute('business_id', TenantContext::businessId());
            }
        });
    }

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }
}
