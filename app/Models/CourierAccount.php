<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Core\Concerns\BelongsToBusiness;

class CourierAccount extends Model
{
    use HasFactory, BelongsToBusiness;

    protected $fillable = array (
  0 => 'business_id',
  1 => 'provider',
  2 => 'name',
  3 => 'is_mock',
  4 => 'is_active',
  5 => 'credentials',
  6 => 'settings',
);

    protected function casts(): array
    {
        return array (
  'is_mock' => 'boolean',
  'is_active' => 'boolean',
  'credentials' => 'encrypted:array',
  'settings' => 'array',
);
    }


}
