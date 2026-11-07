<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Core\Concerns\BelongsToBusiness;

class AttendanceLog extends Model
{
    use HasFactory, BelongsToBusiness;

    protected $table = 'hr_attendance_logs';

    protected $fillable = array (
  0 => 'business_id',
  1 => 'employee_id',
  2 => 'date',
  3 => 'check_in',
  4 => 'check_out',
  5 => 'source',
  6 => 'status',
);

    protected function casts(): array
    {
        return array (
  'date' => 'date',
  'check_in' => 'datetime',
  'check_out' => 'datetime',
);
    }


}
