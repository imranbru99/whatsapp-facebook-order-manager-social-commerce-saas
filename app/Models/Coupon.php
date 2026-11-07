<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Core\Concerns\BelongsToBusiness;

class Coupon extends Model
{
    use HasFactory, BelongsToBusiness;

    protected $fillable = array (
  0 => 'business_id',
  1 => 'code',
  2 => 'type',
  3 => 'value',
  4 => 'min_order',
  5 => 'usage_limit',
  6 => 'used_count',
  7 => 'starts_at',
  8 => 'ends_at',
  9 => 'is_active',
);

    protected function casts(): array
    {
        return array (
  'starts_at' => 'datetime',
  'ends_at' => 'datetime',
  'is_active' => 'boolean',
);
    }


}
