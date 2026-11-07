<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Core\Concerns\BelongsToBusiness;

class CustomerAddress extends Model
{
    use HasFactory, BelongsToBusiness;

    protected $fillable = array (
  0 => 'business_id',
  1 => 'customer_id',
  2 => 'label',
  3 => 'line1',
  4 => 'line2',
  5 => 'district',
  6 => 'thana',
  7 => 'area',
  8 => 'postal_code',
  9 => 'country',
  10 => 'is_default',
);

    protected function casts(): array
    {
        return array (
  'is_default' => 'boolean',
);
    }


    public function customer() { return $this->belongsTo(Customer::class); }

}
