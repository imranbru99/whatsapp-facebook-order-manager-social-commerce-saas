<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use ImranDev\UniversalSlug\Attributes\Slug;
use ImranDev\UniversalSlug\Traits\HasUniversalSlug;

#[Slug(from: "name", to: "slug", skipIfFilled: true)]
class Business extends Model
{
    use HasFactory, SoftDeletes, HasUniversalSlug;

    protected $fillable = array (
  0 => 'ulid',
  1 => 'name',
  2 => 'slug',
  3 => 'type',
  4 => 'email',
  5 => 'phone',
  6 => 'currency',
  7 => 'timezone',
  8 => 'locale',
  9 => 'logo_path',
  10 => 'settings',
  11 => 'is_demo',
  12 => 'is_active',
  13 => 'plan_id',
  14 => 'trial_ends_at',
);

    protected function casts(): array
    {
        return array (
  'settings' => 'array',
  'is_demo' => 'boolean',
  'is_active' => 'boolean',
  'trial_ends_at' => 'datetime',
);
    }


    public function plan() { return $this->belongsTo(Plan::class); }
    public function branches() { return $this->hasMany(Branch::class); }
    public function modules() { return $this->hasMany(BusinessModule::class); }
    public function users() { return $this->hasMany(User::class); }
    public function defaultBranch() { return $this->hasOne(Branch::class)->where("is_default", true); }

}
