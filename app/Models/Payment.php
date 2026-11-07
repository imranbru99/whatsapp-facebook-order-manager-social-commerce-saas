<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Core\Concerns\BelongsToBusiness;

class Payment extends Model
{
    use HasFactory, BelongsToBusiness;

    protected $fillable = array (
  0 => 'business_id',
  1 => 'order_id',
  2 => 'customer_id',
  3 => 'method',
  4 => 'gateway',
  5 => 'status',
  6 => 'amount',
  7 => 'currency',
  8 => 'trx_id',
  9 => 'proof_path',
  10 => 'meta',
  11 => 'paid_at',
);

    protected function casts(): array
    {
        return array (
  'meta' => 'array',
  'paid_at' => 'datetime',
);
    }


    public function order() { return $this->belongsTo(Order::class); }

}
