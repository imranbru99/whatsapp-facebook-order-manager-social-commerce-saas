<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Core\Concerns\BelongsToBusiness;

class Lead extends Model
{
    use HasFactory, BelongsToBusiness;

    protected $fillable = array (
  0 => 'business_id',
  1 => 'customer_id',
  2 => 'assigned_to',
  3 => 'name',
  4 => 'phone',
  5 => 'stage',
  6 => 'value',
  7 => 'source',
  8 => 'notes',
);

    protected function casts(): array
    {
        return array (
);
    }


}
