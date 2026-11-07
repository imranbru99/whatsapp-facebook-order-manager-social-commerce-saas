<?php

namespace App\Core\Concerns;

use App\Models\Branch;
use App\Support\TenantContext;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToBranch
{
    public static function bootBelongsToBranch(): void
    {
        static::addGlobalScope('branch', function (Builder $builder): void {
            $branchId = TenantContext::branchId();
            if ($branchId) {
                $builder->where($builder->getModel()->getTable().'.branch_id', $branchId);
            }
        });

        static::creating(function (Model $model): void {
            if (! $model->getAttribute('branch_id') && TenantContext::branchId()) {
                $model->setAttribute('branch_id', TenantContext::branchId());
            }
        });
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }
}
