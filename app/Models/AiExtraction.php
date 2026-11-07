<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Core\Concerns\BelongsToBusiness;

class AiExtraction extends Model
{
    use HasFactory, BelongsToBusiness;

    protected $fillable = array (
  0 => 'business_id',
  1 => 'conversation_id',
  2 => 'order_id',
  3 => 'source_text',
  4 => 'extracted',
  5 => 'confidence',
  6 => 'used_mock',
);

    protected function casts(): array
    {
        return array (
  'extracted' => 'array',
  'used_mock' => 'boolean',
);
    }


}
