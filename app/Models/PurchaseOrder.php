<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Core\Concerns\BelongsToBusiness;
use App\Core\Concerns\BelongsToBranch;

class PurchaseOrder extends Model
{
    use HasFactory, BelongsToBusiness, BelongsToBranch;

    protected $fillable = array (
  0 => 'business_id',
  1 => 'branch_id',
  2 => 'supplier_id',
  3 => 'number',
  4 => 'status',
  5 => 'total',
  6 => 'ordered_at',
  7 => 'received_at',
);

    protected function casts(): array
    {
        return array (
  'ordered_at' => 'datetime',
  'received_at' => 'datetime',
);
    }


    public function items() { return $this->hasMany(PurchaseOrderItem::class); }
    public function supplier() { return $this->belongsTo(Supplier::class); }

}
