<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = array (
  0 => 'name',
  1 => 'slug',
  2 => 'code',
  3 => 'price_monthly',
  4 => 'price_yearly',
  5 => 'currency',
  6 => 'order_limit',
  7 => 'ai_credit_limit',
  8 => 'staff_limit',
  9 => 'channel_limit',
  10 => 'features',
  11 => 'is_active',
);

    protected function casts(): array
    {
        return array (
  'features' => 'array',
  'is_active' => 'boolean',
);
    }


    public function businesses()
    {
        return $this->hasMany(Business::class);
    }
}
