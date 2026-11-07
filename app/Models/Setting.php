<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = array (
  0 => 'settable_type',
  1 => 'settable_id',
  2 => 'group',
  3 => 'key',
  4 => 'value',
);

    protected function casts(): array
    {
        return array (
  'value' => 'array',
);
    }


}
