<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'name', 'slug', 'short_description', 'description', 'specifications',
        'applications', 'category_id', 'subcategory_id', 'brand', 'manufacturer',
        'price', 'unit', 'stock', 'min_stock', 'stock_apartado', 'stock_vendido',
        'is_active', 'is_featured', 'images', 'variants', 'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'images' => 'array',
        'variants' => 'array',
        'price' => 'decimal:2',
        'stock' => 'integer',
        'stock_apartado' => 'integer',
        'stock_vendido' => 'integer',
        'min_stock' => 'integer',
        'views' => 'integer',
        'sort_order' => 'integer',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function subcategory(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'subcategory_id');
    }

    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    protected function mainImage(): Attribute
    {
        return Attribute::get(fn () => $this->images[0] ?? null);
    }

    protected function inStock(): Attribute
    {
        return Attribute::get(fn (): bool => $this->stock_disponible > 0);
    }

    protected function stockDisponible(): Attribute
    {
        return Attribute::get(fn (): int => max(
            0,
            (int) $this->stock - (int) $this->stock_apartado - (int) $this->stock_vendido
        ));
    }

    protected function priceFormatted(): Attribute
    {
        return Attribute::get(fn (): string => is_null($this->price)
            ? 'Consultar'
            : 'Bs '.number_format((float) $this->price, 2, '.', ','));
    }

    protected static function booted(): void
    {
        static::creating(function (self $product) {
            if (blank($product->slug)) {
                $product->slug = Str::slug($product->name).'-'.Str::lower($product->code);
            }
        });
    }
}
