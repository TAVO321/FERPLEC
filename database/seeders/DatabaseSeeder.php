<?php

namespace Database\Seeders;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Categorías raíz y sus subcategorías.
     *
     * @var array<int, array{name: string, slug: string, children: array<int, array{name: string, slug: string}>}>
     */
    private const CATEGORIES = [
        [
            'name' => 'Plomería',
            'slug' => 'plomeria',
            'children' => [
                ['name' => 'Tuberías PVC', 'slug' => 'tuberias-pvc'],
                ['name' => 'Accesorios Sanitarios', 'slug' => 'accesorios-sanitarios'],
            ],
        ],
        [
            'name' => 'Electricidad',
            'slug' => 'electricidad',
            'children' => [
                ['name' => 'Cables y Conductores', 'slug' => 'cables-conductores'],
                ['name' => 'Iluminación', 'slug' => 'iluminacion'],
                ['name' => 'Accesorios Eléctricos', 'slug' => 'accesorios-electricos'],
            ],
        ],
        [
            'name' => 'Ferretería y Construcción',
            'slug' => 'ferreteria-construccion',
            'children' => [
                ['name' => 'Herramientas', 'slug' => 'herramientas'],
                ['name' => 'Construcción', 'slug' => 'construccion'],
            ],
        ],
    ];

    public function run(): void
    {
        $this->seedAdmin();
        $this->seedCategories();
        $this->seedBanner();
        $this->seedSettings();

        $this->call(TipSeeder::class);
    }

    private function seedAdmin(): void
    {
        $email = env('SEED_ADMIN_EMAIL', 'admin@ferplec.com');
        $plainPassword = env('SEED_ADMIN_PASSWORD');

        $attributes = [
            'name' => 'Administrador FERPLEC',
            'email_verified_at' => now(),
            'is_admin' => true,
        ];

        $user = User::firstOrNew(['email' => $email]);

        if (! $user->exists) {
            $attributes['password'] = Hash::make($plainPassword ?: Str::random(16));
        }

        $user->fill($attributes)->save();

        if (! $user->wasRecentlyCreated && $plainPassword) {
            $user->forceFill(['password' => Hash::make($plainPassword)])->save();
        }
    }

    private function seedCategories(): void
    {
        foreach (self::CATEGORIES as $index => $category) {
            $parent = Category::updateOrCreate(
                ['slug' => $category['slug']],
                [
                    'name' => $category['name'],
                    'parent_id' => null,
                    'is_active' => true,
                    'is_featured' => true,
                    'sort_order' => $index + 1,
                ]
            );

            foreach ($category['children'] as $childIndex => $child) {
                Category::updateOrCreate(
                    ['slug' => $child['slug']],
                    [
                        'name' => $child['name'],
                        'parent_id' => $parent->id,
                        'is_active' => true,
                        'is_featured' => false,
                        'sort_order' => $childIndex + 1,
                    ]
                );
            }
        }
    }

    private function seedBanner(): void
    {
        Banner::updateOrCreate(
            ['position' => 'home_hero'],
            [
                'title' => 'Todo para tus proyectos.',
                'subtitle' => 'Explora nuestros productos, arma tu lista y envíala por WhatsApp.',
                'description' => 'Materiales que hacen posibles tus ideas.',
                'image' => 'banners/banner-hero.jpg',
                'button_text' => 'Ver catálogo',
                'button_link' => '/productos',
                'is_active' => true,
                'sort_order' => 1,
            ]
        );
    }

    private function seedSettings(): void
    {
        Setting::set('whatsapp', env('SEED_WHATSAPP_NUMBER', '59100000000'));
        Setting::set('site_name', 'FERPLEC');
        Setting::set('site_slogan', 'Ferretería, Plomería y Electricidad fácil');
    }
}
