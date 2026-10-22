<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CatalogFilterTest extends TestCase
{
    use RefreshDatabase;

    private function seedCatalog(): void
    {
        $plomeria = Category::create(['name' => 'Plomería', 'slug' => 'plomeria', 'is_active' => true]);
        $tuberias = Category::create(['name' => 'Tuberías PVC', 'slug' => 'tuberias-pvc', 'parent_id' => $plomeria->id, 'is_active' => true]);
        $sanitarios = Category::create(['name' => 'Accesorios Sanitarios', 'slug' => 'accesorios-sanitarios', 'parent_id' => $plomeria->id, 'is_active' => true]);

        Product::create(['code' => 'PL1', 'name' => 'Tubería PVC E-40', 'category_id' => $plomeria->id, 'subcategory_id' => $tuberias->id]);
        Product::create(['code' => 'PL2', 'name' => 'Codo PVC sanitario', 'category_id' => $plomeria->id, 'subcategory_id' => $sanitarios->id]);
    }

    public function test_parent_category_returns_all_its_products(): void
    {
        $this->seedCatalog();

        $response = $this->get('/productos?category='.$this->categoryId('plomeria'));

        $response->assertOk();
        $response->assertSee('Tubería PVC E-40');
        $response->assertSee('Codo PVC sanitario');
    }

    public function test_subcategory_filter_returns_only_that_subcategory(): void
    {
        $this->seedCatalog();

        $response = $this->get('/productos?category='.$this->categoryId('tuberias-pvc'));

        $response->assertOk();
        $response->assertSee('Tubería PVC E-40');
        $response->assertDontSee('Codo PVC sanitario');
    }

    private function categoryId(string $slug): int
    {
        return Category::where('slug', $slug)->firstOrFail()->id;
    }
}
