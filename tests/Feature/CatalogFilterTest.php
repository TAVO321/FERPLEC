<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CatalogFilterTest extends TestCase
{
    use RefreshDatabase;

    private Category $parent;

    private Category $tuberias;

    private Category $sanitarios;

    protected function setUp(): void
    {
        parent::setUp();

        $this->parent = Category::factory()->create(['name' => 'Plomería', 'slug' => 'plomeria']);
        $this->tuberias = Category::factory()->childOf($this->parent)->create(['name' => 'Tuberías PVC', 'slug' => 'tuberias-pvc']);
        $this->sanitarios = Category::factory()->childOf($this->parent)->create(['name' => 'Accesorios Sanitarios', 'slug' => 'accesorios-sanitarios']);

        Product::factory()->inCategory($this->tuberias)->create(['code' => 'PL1', 'name' => 'Tubería PVC E-40']);
        Product::factory()->inCategory($this->sanitarios)->create(['code' => 'PL2', 'name' => 'Codo PVC sanitario']);
    }

    public function test_parent_category_returns_all_its_products(): void
    {
        $this->get('/productos?category='.$this->parent->id)
            ->assertOk()
            ->assertSee('Tubería PVC E-40')
            ->assertSee('Codo PVC sanitario');
    }

    public function test_subcategory_filter_returns_only_that_subcategory(): void
    {
        $this->get('/productos?category='.$this->tuberias->id)
            ->assertOk()
            ->assertSee('Tubería PVC E-40')
            ->assertDontSee('Codo PVC sanitario');
    }

    public function test_inactive_products_are_not_listed(): void
    {
        Product::factory()->inactive()->create(['name' => 'Producto Oculto']);

        $this->get('/productos')
            ->assertOk()
            ->assertDontSee('Producto Oculto');
    }

    public function test_catalog_can_be_sorted_by_price(): void
    {
        Product::factory()->create(['name' => 'Producto Barato', 'price' => 10]);
        Product::factory()->create(['name' => 'Producto Caro', 'price' => 900]);

        $response = $this->get('/productos?sort=precio_asc')->assertOk();

        $positions = [
            strpos($response->getContent(), 'Producto Barato'),
            strpos($response->getContent(), 'Producto Caro'),
        ];

        $this->assertNotFalse($positions[0]);
        $this->assertNotFalse($positions[1]);
        $this->assertLessThan($positions[1], $positions[0]);
    }

    public function test_catalog_search_matches_code(): void
    {
        $this->get('/productos?q=PL1')
            ->assertOk()
            ->assertSee('Tubería PVC E-40')
            ->assertDontSee('Codo PVC sanitario');
    }
}
