<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use ImranDev\UniversalSlug\Attributes\Slug;
use ImranDev\UniversalSlug\Traits\HasUniversalSlug;

#[Slug(from: "title", to: "slug", skipIfFilled: true)]
class CmsPage extends Model
{
    use HasFactory, HasUniversalSlug;

    protected $fillable = array (
  0 => 'slug',
  1 => 'title',
  2 => 'locale',
  3 => 'meta_title',
  4 => 'meta_description',
  5 => 'body',
  6 => 'is_published',
);

    protected function casts(): array
    {
        return array (
  'is_published' => 'boolean',
);
    }


}
