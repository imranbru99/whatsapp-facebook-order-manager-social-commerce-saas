<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Core\Concerns\BelongsToBusiness;

class Subscription extends Model
{
    use HasFactory, BelongsToBusiness;

    protected $fillable = array (
  0 => 'business_id',
  1 => 'plan_id',
  2 => 'status',
  3 => 'starts_at',
  4 => 'ends_at',
  5 => 'renews_at',
);

    protected function casts(): array
    {
        return array (
  'starts_at' => 'datetime',
  'ends_at' => 'datetime',
  'renews_at' => 'datetime',
);
    }


    public function plan() { return $this->belongsTo(Plan::class); }

}
