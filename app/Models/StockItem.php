<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Core\Concerns\BelongsToBusiness;

class StockItem extends Model
{
    use HasFactory, BelongsToBusiness;

    protected $fillable = array (
  0 => 'business_id',
  1 => 'warehouse_id',
  2 => 'product_id',
  3 => 'product_variant_id',
  4 => 'quantity',
  5 => 'reserved',
  6 => 'reorder_point',
);

    protected function casts(): array
    {
        return array (
);
    }


    public function product() { return $this->belongsTo(Product::class); }
    public function warehouse() { return $this->belongsTo(Warehouse::class); }
    public function available(): int { return max(0, (int) $this->quantity - (int) $this->reserved); }

}
