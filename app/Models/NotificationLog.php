<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NotificationLog extends Model
{
    use HasFactory;

    protected $fillable = array (
  0 => 'business_id',
  1 => 'user_id',
  2 => 'channel',
  3 => 'event',
  4 => 'title',
  5 => 'body',
  6 => 'read_at',
);

    protected function casts(): array
    {
        return array (
  'read_at' => 'datetime',
);
    }

    protected $table = "notifications_log";


}
