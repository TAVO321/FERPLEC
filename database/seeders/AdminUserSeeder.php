<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@ferplec.com'],
            [
                'name' => 'Admin FERPLEC',
                'password' => 'ferplec2026',
                'email_verified_at' => now(),
            ],
        );
    }
}
