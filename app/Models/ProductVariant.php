<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Core\Concerns\BelongsToBusiness;

class ProductVariant extends Model
{
    use HasFactory, BelongsToBusiness;

    protected $fillable = array (
  0 => 'business_id',
  1 => 'product_id',
  2 => 'name',
  3 => 'sku',
  4 => 'barcode',
  5 => 'attributes',
  6 => 'price',
  7 => 'cost_price',
  8 => 'is_active',
);

    protected function casts(): array
    {
        return array (
  'attributes' => 'array',
  'is_active' => 'boolean',
);
    }


    public function product() { return $this->belongsTo(Product::class); }

}
