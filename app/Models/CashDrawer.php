<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Core\Concerns\BelongsToBusiness;
use App\Core\Concerns\BelongsToBranch;

class CashDrawer extends Model
{
    use HasFactory, BelongsToBusiness, BelongsToBranch;

    protected $fillable = array (
  0 => 'business_id',
  1 => 'branch_id',
  2 => 'name',
  3 => 'opening_balance',
  4 => 'current_balance',
  5 => 'is_open',
);

    protected function casts(): array
    {
        return array (
  'is_open' => 'boolean',
);
    }


}
