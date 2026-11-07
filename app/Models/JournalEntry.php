<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Core\Concerns\BelongsToBusiness;

class JournalEntry extends Model
{
    use HasFactory, BelongsToBusiness;

    protected $fillable = array (
  0 => 'business_id',
  1 => 'number',
  2 => 'entry_date',
  3 => 'memo',
  4 => 'reference_type',
  5 => 'reference_id',
);

    protected function casts(): array
    {
        return array (
  'entry_date' => 'date',
);
    }


    public function lines() { return $this->hasMany(JournalLine::class); }

}
