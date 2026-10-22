<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
    ];

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($tip) {
            if (empty($tip->slug)) {
                $tip->slug = Str::slug($tip->title);
            }
            if (empty($tip->published_at)) {
                $tip->published_at = now();
            }
        });
    }

    public function comments()
    {
        return $this->hasMany(TipComment::class);
    }

    public function approvedComments()
    {
        return $this->hasMany(TipComment::class)->where('is_approved', true);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
