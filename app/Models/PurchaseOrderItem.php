<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PurchaseOrderItem extends Model
{
    use HasFactory;

    protected $fillable = array (
  0 => 'purchase_order_id',
  1 => 'product_id',
  2 => 'product_variant_id',
  3 => 'quantity',
  4 => 'unit_cost',
  5 => 'received_quantity',
);

    protected function casts(): array
    {
        return array (
);
    }


}
