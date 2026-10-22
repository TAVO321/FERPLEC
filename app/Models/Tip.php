<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Tip extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'slug', 'category', 'image', 'excerpt', 'content',
        'read_time', 'is_published', 'is_featured', 'related_products', 'published_at',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'is_featured' => 'boolean',
        'related_products' => 'array',
        'published_at' => 'datetime',
        'read_time' => 'integer',
    ];

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(TipComment::class);
    }

    public function approvedComments(): HasMany
    {
        return $this->hasMany(TipComment::class)->where('is_approved', true);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected function areaLabel(): Attribute
    {
        return Attribute::get(fn (): string => $this->category ?: 'General');
    }

    protected static function booted(): void
    {
        static::creating(function (self $tip) {
            if (blank($tip->slug)) {
                $tip->slug = Str::slug($tip->title);
            }
            if (blank($tip->published_at)) {
                $tip->published_at = now();
            }
        });
    }
}
