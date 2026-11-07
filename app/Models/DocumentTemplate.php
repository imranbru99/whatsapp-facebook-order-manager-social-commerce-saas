<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DocumentTemplate extends Model
{
    use HasFactory;

    protected $fillable = array (
  0 => 'business_id',
  1 => 'key',
  2 => 'name',
  3 => 'type',
  4 => 'locale',
  5 => 'body_html',
  6 => 'is_default',
);

    protected function casts(): array
    {
        return array (
  'is_default' => 'boolean',
);
    }


}
