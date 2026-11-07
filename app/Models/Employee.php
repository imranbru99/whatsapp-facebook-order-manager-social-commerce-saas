<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Core\Concerns\BelongsToBusiness;
use App\Core\Concerns\BelongsToBranch;

class Employee extends Model
{
    use HasFactory, BelongsToBusiness, BelongsToBranch;

    protected $fillable = array (
  0 => 'business_id',
  1 => 'branch_id',
  2 => 'user_id',
  3 => 'employee_code',
  4 => 'name',
  5 => 'phone',
  6 => 'designation',
  7 => 'department',
  8 => 'joined_at',
  9 => 'salary',
  10 => 'is_active',
);

    protected function casts(): array
    {
        return array (
  'joined_at' => 'date',
  'is_active' => 'boolean',
);
    }


}
