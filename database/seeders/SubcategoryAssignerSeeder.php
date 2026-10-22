<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class SubcategoryAssignerSeeder extends Seeder
{
    /**
     * Reglas de asignacion: slug de categoria padre => [
     *   'match'   => [slug subcategoria => regex sobre el nombre del producto],
     *   'default' => slug subcategoria usada cuando ninguna regla aplica,
     * ]
     */
    private const RULES = [
        'plomeria' => [
            'match' => [
                'accesorios-sanitarios' => '/sanitar|llave de paso/i',
            ],
            'default' => 'tuberias-pvc',
        ],
        'electricidad' => [
            'match' => [
                'iluminacion' => '/foco|soquete|placa|pulsador|timbre|luminaria|lampara/i',
                'cables-conductores' => '/cable|conduit|alargador|adaptador|conector|regleta/i',
            ],
            'default' => 'accesorios-electricos',
        ],
        'ferreteria-construccion' => [
            'match' => [
                'herramientas' => '/destornillador|dado|flex[oó]metro|balanza|herramienta/i',
            ],
            'default' => 'construccion',
        ],
    ];

    /**
     * Subcategorias que deben existir (slug => [nombre, padre, orden]).
     */
    private const SUBCATEGORIES = [
        'tuberias-pvc' => ['Tuberías PVC', 'plomeria', 1],
        'accesorios-sanitarios' => ['Accesorios Sanitarios', 'plomeria', 2],
        'cables-conductores' => ['Cables y Conductores', 'electricidad', 1],
        'iluminacion' => ['Iluminación', 'electricidad', 2],
        'accesorios-electricos' => ['Accesorios Eléctricos', 'electricidad', 3],
        'herramientas' => ['Herramientas', 'ferreteria-construccion', 1],
        'construccion' => ['Construcción', 'ferreteria-construccion', 2],
    ];

    public function run(): void
    {
        $this->ensureCategories();

        foreach (self::RULES as $parentSlug => $config) {
            $parent = Category::where('slug', $parentSlug)->first();

            if (! $parent) {
                $this->command?->warn("Categoria padre no encontrada: {$parentSlug}");

                continue;
            }

            $subcategories = $parent->children()->pluck('id', 'slug');
            $products = Product::where('category_id', $parent->id)->get();

            foreach ($products as $product) {
                $subcategoryId = null;

                foreach ($config['match'] as $slug => $pattern) {
                    if (isset($subcategories[$slug]) && preg_match($pattern, $product->name)) {
                        $subcategoryId = $subcategories[$slug];
                        break;
                    }
                }

                $subcategoryId ??= $subcategories[$config['default']] ?? null;

                if ($subcategoryId !== $product->subcategory_id) {
                    $product->subcategory_id = $subcategoryId;
                    $product->save();
                }
            }

            $this->command?->info("{$parent->name}: {$products->count()} productos asignados");
        }

        $this->command?->table(
            ['Subcategoria', 'Productos'],
            Category::whereNotNull('parent_id')->orderBy('parent_id')->orderBy('sort_order')->get()
                ->map(fn ($c) => [$c->name, Product::where('subcategory_id', $c->id)->count()])
                ->all()
        );
    }

    private function ensureCategories(): void
    {
        foreach (self::SUBCATEGORIES as $slug => [$name, $parentSlug, $sortOrder]) {
            $parent = Category::where('slug', $parentSlug)->first();

            if (! $parent) {
                continue;
            }

            Category::firstOrCreate(
                ['slug' => $slug],
                [
                    'name' => $name,
                    'parent_id' => $parent->id,
                    'is_active' => true,
                    'sort_order' => $sortOrder,
                ]
            );
        }
    }
}
