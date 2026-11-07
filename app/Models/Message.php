<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Core\Concerns\BelongsToBusiness;

class Message extends Model
{
    use HasFactory, BelongsToBusiness;

    protected $fillable = array (
  0 => 'business_id',
  1 => 'conversation_id',
  2 => 'user_id',
  3 => 'direction',
  4 => 'sender_type',
  5 => 'external_id',
  6 => 'body',
  7 => 'content_type',
  8 => 'attachments',
  9 => 'meta',
  10 => 'sent_at',
);

    protected function casts(): array
    {
        return array (
  'attachments' => 'array',
  'meta' => 'array',
  'sent_at' => 'datetime',
);
    }


    public function conversation() { return $this->belongsTo(Conversation::class); }

}
