<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Core\Concerns\BelongsToBusiness;

class Supplier extends Model
{
    use HasFactory, BelongsToBusiness;

    protected $fillable = array (
  0 => 'business_id',
  1 => 'name',
  2 => 'phone',
  3 => 'email',
  4 => 'address',
);

    protected function casts(): array
    {
        return array (
);
    }


}
