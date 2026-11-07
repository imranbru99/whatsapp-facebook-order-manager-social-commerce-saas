<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Core\Concerns\BelongsToBusiness;
use ImranDev\UniversalSlug\Attributes\Slug;
use ImranDev\UniversalSlug\Traits\HasUniversalSlug;

#[Slug(from: "name", to: "slug", skipIfFilled: true)]
class Category extends Model
{
    use HasFactory, BelongsToBusiness, HasUniversalSlug;

    protected $fillable = array (
  0 => 'business_id',
  1 => 'parent_id',
  2 => 'name',
  3 => 'slug',
);

    protected function casts(): array
    {
        return array (
);
    }


}
