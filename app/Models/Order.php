<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Core\Concerns\BelongsToBusiness;
use App\Core\Concerns\BelongsToBranch;

class Order extends Model
{
    use HasFactory, SoftDeletes, BelongsToBusiness, BelongsToBranch;

    protected $fillable = array (
  0 => 'ulid',
  1 => 'business_id',
  2 => 'branch_id',
  3 => 'customer_id',
  4 => 'conversation_id',
  5 => 'assigned_to',
  6 => 'number',
  7 => 'status',
  8 => 'source',
  9 => 'payment_status',
  10 => 'payment_method',
  11 => 'subtotal',
  12 => 'discount_total',
  13 => 'tax_total',
  14 => 'delivery_charge',
  15 => 'grand_total',
  16 => 'paid_total',
  17 => 'due_total',
  18 => 'currency',
  19 => 'shipping_name',
  20 => 'shipping_phone',
  21 => 'shipping_address',
  22 => 'customer_notes',
  23 => 'internal_notes',
  24 => 'public_token',
  25 => 'meta',
  26 => 'confirmed_at',
  27 => 'delivered_at',
);

    protected function casts(): array
    {
        return array (
  'meta' => 'array',
  'confirmed_at' => 'datetime',
  'delivered_at' => 'datetime',
);
    }


    public function items() { return $this->hasMany(OrderItem::class); }
    public function customer() { return $this->belongsTo(Customer::class); }
    public function conversation() { return $this->belongsTo(Conversation::class); }
    public function statusHistories() { return $this->hasMany(OrderStatusHistory::class); }
    public function payments() { return $this->hasMany(Payment::class); }
    public function shipments() { return $this->hasMany(Shipment::class); }

}
