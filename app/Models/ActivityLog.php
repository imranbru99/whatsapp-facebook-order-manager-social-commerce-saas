<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = array (
  0 => 'business_id',
  1 => 'user_id',
  2 => 'action',
  3 => 'subject_type',
  4 => 'subject_id',
  5 => 'properties',
  6 => 'ip',
  7 => 'user_agent',
);

    protected function casts(): array
    {
        return array (
  'properties' => 'array',
);
    }


}
