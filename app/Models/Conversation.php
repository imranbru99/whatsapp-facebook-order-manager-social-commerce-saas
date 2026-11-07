<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Core\Concerns\BelongsToBusiness;
use App\Core\Concerns\BelongsToBranch;

class Conversation extends Model
{
    use HasFactory, BelongsToBusiness, BelongsToBranch;

    protected $fillable = array (
  0 => 'ulid',
  1 => 'business_id',
  2 => 'branch_id',
  3 => 'channel_id',
  4 => 'customer_id',
  5 => 'assigned_to',
  6 => 'external_thread_id',
  7 => 'status',
  8 => 'subject',
  9 => 'unread_count',
  10 => 'last_message_at',
  11 => 'labels',
  12 => 'meta',
);

    protected function casts(): array
    {
        return array (
  'labels' => 'array',
  'meta' => 'array',
  'last_message_at' => 'datetime',
);
    }


    public function messages() { return $this->hasMany(Message::class); }
    public function channel() { return $this->belongsTo(Channel::class); }
    public function customer() { return $this->belongsTo(Customer::class); }

}
