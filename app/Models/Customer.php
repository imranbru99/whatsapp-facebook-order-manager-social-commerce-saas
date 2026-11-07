<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Core\Concerns\BelongsToBusiness;
use App\Core\Concerns\BelongsToBranch;

class Customer extends Model
{
    use HasFactory, SoftDeletes, BelongsToBusiness, BelongsToBranch;

    protected $fillable = array (
  0 => 'ulid',
  1 => 'business_id',
  2 => 'branch_id',
  3 => 'name',
  4 => 'phone',
  5 => 'email',
  6 => 'preferred_channel',
  7 => 'language',
  8 => 'is_vip',
  9 => 'is_blacklisted',
  10 => 'lifetime_value',
  11 => 'risk_score',
  12 => 'tags',
  13 => 'meta',
);

    protected function casts(): array
    {
        return array (
  'is_vip' => 'boolean',
  'is_blacklisted' => 'boolean',
  'tags' => 'array',
  'meta' => 'array',
);
    }


    public function addresses() { return $this->hasMany(CustomerAddress::class); }
    public function orders() { return $this->hasMany(Order::class); }

}
