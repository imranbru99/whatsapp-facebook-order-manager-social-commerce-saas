<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JournalLine extends Model
{
    use HasFactory;

    protected $fillable = array (
  0 => 'journal_entry_id',
  1 => 'account_id',
  2 => 'debit',
  3 => 'credit',
  4 => 'memo',
);

    protected function casts(): array
    {
        return array (
);
    }


    public function account() { return $this->belongsTo(Account::class); }

}
