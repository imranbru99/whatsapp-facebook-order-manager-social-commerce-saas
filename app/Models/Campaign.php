<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Core\Concerns\BelongsToBusiness;

class Campaign extends Model
{
    use HasFactory, BelongsToBusiness;

    protected $fillable = array (
  0 => 'business_id',
  1 => 'name',
  2 => 'channel',
  3 => 'status',
  4 => 'content',
  5 => 'scheduled_at',
  6 => 'sent_at',
  7 => 'recipients_count',
);

    protected function casts(): array
    {
        return array (
  'scheduled_at' => 'datetime',
  'sent_at' => 'datetime',
);
    }


}
