<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Core\Concerns\BelongsToBusiness;
use App\Core\Concerns\BelongsToBranch;

class BusinessModule extends Model
{
    use HasFactory, BelongsToBusiness, BelongsToBranch;

    protected $fillable = array (
  0 => 'business_id',
  1 => 'branch_id',
  2 => 'module_key',
  3 => 'enabled',
  4 => 'settings',
);

    protected function casts(): array
    {
        return array (
  'enabled' => 'boolean',
  'settings' => 'array',
);
    }


}
