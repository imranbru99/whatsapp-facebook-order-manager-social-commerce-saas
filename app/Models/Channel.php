<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Core\Concerns\BelongsToBusiness;
use App\Core\Concerns\BelongsToBranch;

class Channel extends Model
{
    use HasFactory, BelongsToBusiness, BelongsToBranch;

    protected $fillable = array (
  0 => 'business_id',
  1 => 'branch_id',
  2 => 'type',
  3 => 'name',
  4 => 'external_id',
  5 => 'access_token',
  6 => 'status',
  7 => 'is_mock',
  8 => 'settings',
  9 => 'last_synced_at',
);

    protected function casts(): array
    {
        return array (
  'access_token' => 'encrypted',
  'is_mock' => 'boolean',
  'settings' => 'array',
  'last_synced_at' => 'datetime',
);
    }


}
