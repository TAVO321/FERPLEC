<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('settings')) {
            return;
        }

        // `whatsapp_number` quedó duplicada con `whatsapp`; se conserva el
        // valor vigente de `whatsapp` y se elimina la key obsoleta.
        DB::table('settings')->where('key', 'whatsapp_number')->delete();

        DB::table('settings')->updateOrInsert(
            ['key' => 'whatsapp'],
            ['value' => '59163951262', 'updated_at' => now(), 'created_at' => now()]
        );
    }

    public function down(): void
    {
        if (! Schema::hasTable('settings')) {
            return;
        }

        $value = DB::table('settings')->where('key', 'whatsapp')->value('value')
            ?? '59163951262';

        DB::table('settings')->updateOrInsert(
            ['key' => 'whatsapp_number'],
            ['value' => $value, 'updated_at' => now(), 'created_at' => now()]
        );
    }
};
