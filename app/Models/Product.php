<?php

namespace App\Models;

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
        'is_active', 'is_featured', 'images', 'variants', 'views', 'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'images' => 'array',
        'variants' => 'array',
        'price' => 'decimal:2',
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

    public function getMainImageAttribute(): ?string
    {
        $images = $this->images ?? [];

        return $images[0] ?? null;
    }

    public function getInStockAttribute(): bool
    {
        return $this->stock_disponible > 0;
    }

    public function getStockTotalAttribute(): int
    {
        return (int) $this->stock;
    }

    public function getStockDisponibleAttribute(): int
    {
        return max(0, (int) $this->stock - (int) $this->stock_apartado - (int) $this->stock_vendido);
    }

    public function getImageAttribute(): ?string
    {
        return $this->main_image;
    }

    public function getPriceFormattedAttribute(): string
    {
        if (is_null($this->price)) {
            return 'Consultar';
        }

        return 'Bs '.number_format($this->price, 2, '.', ',');
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name).'-'.Str::lower($product->code);
            }
        });
    }
}
