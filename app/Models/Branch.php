<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Core\Concerns\BelongsToBusiness;

class Branch extends Model
{
    use HasFactory, BelongsToBusiness;

    protected $fillable = array (
  0 => 'business_id',
  1 => 'name',
  2 => 'code',
  3 => 'phone',
  4 => 'address',
  5 => 'is_default',
  6 => 'is_active',
);

    protected function casts(): array
    {
        return array (
  'is_default' => 'boolean',
  'is_active' => 'boolean',
);
    }


}
