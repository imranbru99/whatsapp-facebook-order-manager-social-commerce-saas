<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Core\Concerns\BelongsToBusiness;

class StockMovement extends Model
{
    use HasFactory, BelongsToBusiness;

    protected $fillable = array (
  0 => 'business_id',
  1 => 'stock_item_id',
  2 => 'type',
  3 => 'quantity',
  4 => 'reference_type',
  5 => 'reference_id',
  6 => 'notes',
  7 => 'user_id',
);

    protected function casts(): array
    {
        return array (
);
    }


}
