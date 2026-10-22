<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('users') || Schema::hasColumn('users', 'is_admin')) {
            return;
        }

        Schema::table('users', function ($table) {
            $table->boolean('is_admin')->default(false)->after('password')->index();
        });

        // El administradorSeeder crea admin@ferplec.com; cualquier otra
        // cuenta queda con permisos de solo lectura del panel.
        DB::table('users')
            ->where('email', 'admin@ferplec.com')
            ->update(['is_admin' => true]);
    }

    public function down(): void
    {
        if (! Schema::hasTable('users') || ! Schema::hasColumn('users', 'is_admin')) {
            return;
        }

        Schema::table('users', function ($table) {
            $table->dropColumn('is_admin');
        });
    }
};
