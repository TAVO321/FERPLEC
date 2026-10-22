<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Índices para los filtros y ordenamientos que usa la aplicación.
     *
     * Sin ellos, listados como "productos activos ordenados por precio" o
     * "tips publicados por categoría" terminaban en filesort o full scan.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->index(['is_active', 'created_at'], 'products_active_created_idx');
            $table->index(['is_active', 'is_featured', 'sort_order'], 'products_active_featured_idx');
            $table->index(['is_active', 'price'], 'products_active_price_idx');
            $table->index('brand', 'products_brand_idx');
            $table->index('stock', 'products_stock_idx');
        });

        Schema::table('tips', function (Blueprint $table) {
            $table->index(['is_published', 'published_at'], 'tips_published_published_idx');
            $table->index(['is_published', 'category'], 'tips_published_category_idx');
            $table->index(['is_featured', 'published_at'], 'tips_featured_published_idx');
        });

        Schema::table('tip_comments', function (Blueprint $table) {
            $table->index(['tip_id', 'is_approved', 'created_at'], 'tip_comments_tip_approved_created_idx');
        });

        Schema::table('banners', function (Blueprint $table) {
            $table->index(['is_active', 'position', 'sort_order'], 'banners_active_position_sort_idx');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->index(['parent_id', 'is_active', 'sort_order'], 'categories_parent_active_sort_idx');
            $table->index(['is_active', 'sort_order'], 'categories_active_sort_idx');
        });

        Schema::table('stock_movements', function (Blueprint $table) {
            $table->index(['product_id', 'created_at'], 'stock_movements_product_created_idx');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex('products_active_created_idx');
            $table->dropIndex('products_active_featured_idx');
            $table->dropIndex('products_active_price_idx');
            $table->dropIndex('products_brand_idx');
            $table->dropIndex('products_stock_idx');
        });

        Schema::table('tips', function (Blueprint $table) {
            $table->dropIndex('tips_published_published_idx');
            $table->dropIndex('tips_published_category_idx');
            $table->dropIndex('tips_featured_published_idx');
        });

        Schema::table('tip_comments', function (Blueprint $table) {
            $table->dropIndex('tip_comments_tip_approved_created_idx');
        });

        Schema::table('banners', function (Blueprint $table) {
            $table->dropIndex('banners_active_position_sort_idx');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropIndex('categories_parent_active_sort_idx');
            $table->dropIndex('categories_active_sort_idx');
        });

        Schema::table('stock_movements', function (Blueprint $table) {
            $table->dropIndex('stock_movements_product_created_idx');
        });
    }
};
