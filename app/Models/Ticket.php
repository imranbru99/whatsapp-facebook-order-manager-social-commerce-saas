<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Core\Concerns\BelongsToBusiness;

class Ticket extends Model
{
    use HasFactory, BelongsToBusiness;

    protected $fillable = array (
  0 => 'business_id',
  1 => 'customer_id',
  2 => 'order_id',
  3 => 'assigned_to',
  4 => 'number',
  5 => 'subject',
  6 => 'status',
  7 => 'priority',
  8 => 'body',
);

    protected function casts(): array
    {
        return array (
);
    }


}
