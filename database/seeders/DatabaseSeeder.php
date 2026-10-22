<?php

namespace Database\Seeders;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@ferplec.com'],
            ['name' => 'Administrador FERPLEC', 'password' => Hash::make('ferplec2026'), 'email_verified_at' => now()]
        );

        $plomeria = Category::create(['name' => 'Plomería', 'slug' => 'plomeria', 'is_active' => true, 'is_featured' => true, 'sort_order' => 1]);
        $electricidad = Category::create(['name' => 'Electricidad', 'slug' => 'electricidad', 'is_active' => true, 'is_featured' => true, 'sort_order' => 2]);
        $ferreteria = Category::create(['name' => 'Ferretería y Construcción', 'slug' => 'ferreteria-construccion', 'is_active' => true, 'is_featured' => true, 'sort_order' => 3]);

        Category::create(['name' => 'Tuberías PVC', 'slug' => 'tuberias-pvc', 'parent_id' => $plomeria->id, 'is_active' => true, 'sort_order' => 1]);
        Category::create(['name' => 'Accesorios Sanitarios', 'slug' => 'accesorios-sanitarios', 'parent_id' => $plomeria->id, 'is_active' => true, 'sort_order' => 2]);
        Category::create(['name' => 'Cables y Conductores', 'slug' => 'cables-conductores', 'parent_id' => $electricidad->id, 'is_active' => true, 'sort_order' => 1]);
        Category::create(['name' => 'Iluminación', 'slug' => 'iluminacion', 'parent_id' => $electricidad->id, 'is_active' => true, 'sort_order' => 2]);
        Category::create(['name' => 'Accesorios Eléctricos', 'slug' => 'accesorios-electricos', 'parent_id' => $electricidad->id, 'is_active' => true, 'sort_order' => 3]);
        Category::create(['name' => 'Herramientas', 'slug' => 'herramientas', 'parent_id' => $ferreteria->id, 'is_active' => true, 'sort_order' => 1]);
        Category::create(['name' => 'Construcción', 'slug' => 'construccion', 'parent_id' => $ferreteria->id, 'is_active' => true, 'sort_order' => 2]);

        Banner::create([
            'title' => 'Todo para tus proyectos.',
            'subtitle' => 'Explora nuestros productos, arma tu lista y envíala por WhatsApp.',
            'description' => 'Materiales que hacen posibles tus ideas.',
            'image' => 'banners/banner-hero.jpg',
            'button_text' => 'Ver catálogo',
            'button_link' => '/productos',
            'position' => 'home_hero',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $bannerSrc = public_path('images/banner-hero.jpg');
        if (file_exists($bannerSrc)) {
            @mkdir(storage_path('app/public/banners'), 0755, true);
            @copy($bannerSrc, storage_path('app/public/banners/banner-hero.jpg'));
        }

        $this->call(TipSeeder::class);

        Setting::set('whatsapp_number', '59100000000');
        Setting::set('site_name', 'FERPLEC');
        Setting::set('site_slogan', 'Ferretería, Plomería y Electricidad fácil');
    }
}
