<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post extends Model
{
    public const TYPES = ['portfolio', 'article'];

    protected $fillable = ['category_id', 'type', 'title', 'excerpt', 'image_url', 'url', 'is_featured'];

    protected $casts = ['is_featured' => 'boolean'];

    // Full image URL for the admin preview (seeded images live in the frontend folder)
    public function previewUrl(): ?string
    {
        if (! $this->image_url) {
            return null;
        }

        return str_starts_with($this->image_url, 'http')
            ? $this->image_url
            : config('app.frontend_url').'/'.$this->image_url;
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
