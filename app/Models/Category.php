<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;

class Category extends Model
{
    use HasFactory;

    public const CACHE_KEY = 'ferplec.categories.list';

    public const CACHE_TTL = 3600;

    protected $fillable = [
        'name', 'slug', 'description', 'image', 'icon', 'color',
        'parent_id', 'sort_order', 'is_active', 'is_featured',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'sort_order' => 'integer',
        'parent_id' => 'integer',
    ];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id')
            ->where('is_active', true)
            ->orderBy('sort_order');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function subcategoryProducts(): HasMany
    {
        return $this->hasMany(Product::class, 'subcategory_id');
    }

    /**
     * Listado cacheado de categorías para los formularios del panel.
     *
     * Se cachean arrays planos (no modelos) para que funcione con
     * cualquier driver de caché sin depender de la serialización de
     * objetos Eloquent.
     *
     * @return Collection<int, Category>
     */
    public static function listCached(): Collection
    {
        $rows = Cache::remember(
            self::CACHE_KEY,
            self::CACHE_TTL,
            fn (): array => static::query()
                ->orderBy('sort_order')
                ->get(['id', 'name', 'icon', 'parent_id', 'sort_order'])
                ->toArray()
        );

        return static::hydrate($rows);
    }

    public static function flushCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }
}
