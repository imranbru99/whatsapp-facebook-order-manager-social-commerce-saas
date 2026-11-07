<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ShipmentEvent extends Model
{
    use HasFactory;

    protected $fillable = array (
  0 => 'shipment_id',
  1 => 'status',
  2 => 'description',
  3 => 'happened_at',
);

    protected function casts(): array
    {
        return array (
  'happened_at' => 'datetime',
);
    }


}
