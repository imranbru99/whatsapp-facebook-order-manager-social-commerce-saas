<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use ImranDev\UniversalSlug\Attributes\Slug;
use ImranDev\UniversalSlug\Traits\HasUniversalSlug;

#[Slug(from: "title", to: "slug", skipIfFilled: true)]
class BlogPost extends Model
{
    use HasFactory, HasUniversalSlug;

    protected $fillable = array (
  0 => 'slug',
  1 => 'title',
  2 => 'locale',
  3 => 'excerpt',
  4 => 'body',
  5 => 'is_published',
  6 => 'published_at',
);

    protected function casts(): array
    {
        return array (
  'is_published' => 'boolean',
  'published_at' => 'datetime',
);
    }


}
