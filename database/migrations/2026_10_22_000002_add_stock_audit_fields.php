<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->integer('stock_apartado')->default(0)->after('stock');
            $table->integer('stock_vendido')->default(0)->after('stock_apartado');
        });

        Schema::table('stock_movements', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('product_id')->constrained()->nullOnDelete();
            $table->string('field')->nullable()->after('type');
            $table->string('previous_value')->nullable()->after('field');
            $table->string('new_value')->nullable()->after('previous_value');
        });
    }

    public function down(): void
    {
        Schema::table('stock_movements', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id', 'field', 'previous_value', 'new_value']);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['stock_apartado', 'stock_vendido']);
        });
    }
};
