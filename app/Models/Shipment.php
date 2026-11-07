<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Core\Concerns\BelongsToBusiness;

class Shipment extends Model
{
    use HasFactory, BelongsToBusiness;

    protected $fillable = array (
  0 => 'business_id',
  1 => 'order_id',
  2 => 'courier_account_id',
  3 => 'rider_id',
  4 => 'tracking_number',
  5 => 'status',
  6 => 'cod_amount',
  7 => 'courier_charge',
  8 => 'meta',
  9 => 'shipped_at',
  10 => 'delivered_at',
);

    protected function casts(): array
    {
        return array (
  'meta' => 'array',
  'shipped_at' => 'datetime',
  'delivered_at' => 'datetime',
);
    }


    public function order() { return $this->belongsTo(Order::class); }
    public function events() { return $this->hasMany(ShipmentEvent::class); }
    public function courierAccount() { return $this->belongsTo(CourierAccount::class); }

}
