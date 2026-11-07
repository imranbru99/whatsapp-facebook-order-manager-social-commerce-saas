<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CashMovement extends Model
{
    use HasFactory;

    protected $fillable = array (
  0 => 'cash_drawer_id',
  1 => 'user_id',
  2 => 'type',
  3 => 'amount',
  4 => 'reason',
);

    protected function casts(): array
    {
        return array (
);
    }


}
