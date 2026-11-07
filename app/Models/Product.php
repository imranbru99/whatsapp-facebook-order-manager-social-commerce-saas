<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Core\Concerns\BelongsToBusiness;
use ImranDev\UniversalSlug\Attributes\Slug;
use ImranDev\UniversalSlug\Traits\HasUniversalSlug;

#[Slug(from: "name", to: "slug", skipIfFilled: true)]
class Product extends Model
{
    use HasFactory, SoftDeletes, BelongsToBusiness, HasUniversalSlug;

    protected $fillable = array (
  0 => 'ulid',
  1 => 'business_id',
  2 => 'category_id',
  3 => 'brand_id',
  4 => 'name',
  5 => 'slug',
  6 => 'sku',
  7 => 'description',
  8 => 'price',
  9 => 'cost_price',
  10 => 'track_inventory',
  11 => 'is_active',
  12 => 'meta',
);

    protected function casts(): array
    {
        return array (
  'track_inventory' => 'boolean',
  'is_active' => 'boolean',
  'meta' => 'array',
);
    }


    public function variants() { return $this->hasMany(ProductVariant::class); }
    public function category() { return $this->belongsTo(Category::class); }
    public function brand() { return $this->belongsTo(Brand::class); }

}
