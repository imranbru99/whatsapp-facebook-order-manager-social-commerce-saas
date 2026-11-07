<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Core\Concerns\BelongsToBusiness;

class OrderItem extends Model
{
    use HasFactory, BelongsToBusiness;

    protected $fillable = array (
  0 => 'business_id',
  1 => 'order_id',
  2 => 'product_id',
  3 => 'product_variant_id',
  4 => 'name',
  5 => 'sku',
  6 => 'quantity',
  7 => 'unit_price',
  8 => 'discount',
  9 => 'tax',
  10 => 'line_total',
  11 => 'meta',
);

    protected function casts(): array
    {
        return array (
  'meta' => 'array',
);
    }


    public function order() { return $this->belongsTo(Order::class); }
    public function product() { return $this->belongsTo(Product::class); }

}
