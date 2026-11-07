<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Core\Concerns\BelongsToBusiness;

class OrderReturn extends Model
{
    use HasFactory, BelongsToBusiness;

    protected $fillable = array (
  0 => 'business_id',
  1 => 'order_id',
  2 => 'number',
  3 => 'status',
  4 => 'reason',
  5 => 'refund_amount',
  6 => 'notes',
);

    protected function casts(): array
    {
        return array (
);
    }

    protected $table = "returns";


    public function order() { return $this->belongsTo(Order::class); }

}
