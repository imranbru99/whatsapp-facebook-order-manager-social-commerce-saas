<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Core\Concerns\BelongsToBusiness;

class OrderStatusHistory extends Model
{
    use HasFactory, BelongsToBusiness;

    protected $fillable = array (
  0 => 'business_id',
  1 => 'order_id',
  2 => 'from_status',
  3 => 'to_status',
  4 => 'user_id',
  5 => 'notes',
);

    protected function casts(): array
    {
        return array (
);
    }


}
