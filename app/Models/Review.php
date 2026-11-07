<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Core\Concerns\BelongsToBusiness;

class Review extends Model
{
    use HasFactory, BelongsToBusiness;

    protected $fillable = array (
  0 => 'business_id',
  1 => 'order_id',
  2 => 'customer_id',
  3 => 'product_id',
  4 => 'rating',
  5 => 'body',
  6 => 'is_published',
);

    protected function casts(): array
    {
        return array (
  'is_published' => 'boolean',
);
    }


}
