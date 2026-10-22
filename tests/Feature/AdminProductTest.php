<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class AdminProductTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->admin()->create();
        $this->actingAs($this->admin);
    }

    public function test_admin_can_list_products(): void
    {
        Product::factory()->create(['name' => 'Producto Panel']);

        $this->get('/admin/products')
            ->assertOk()
            ->assertSee('Producto Panel');
    }

    public function test_admin_can_create_a_product(): void
    {
        $category = Category::factory()->create();

        $this->post('/admin/products', [
            'code' => 'NUEVO1',
            'name' => 'Producto Nuevo',
            'category_id' => $category->id,
            'price' => '25.50',
            'unit' => 'unidad',
            'stock' => 10,
            'min_stock' => 2,
            'stock_apartado' => 0,
            'stock_vendido' => 0,
            'is_active' => 1,
            'is_featured' => 0,
        ])->assertRedirect('/admin/products');

        $this->assertDatabaseHas('products', ['code' => 'NUEVO1', 'name' => 'Producto Nuevo']);
    }

    public function test_admin_can_create_a_product_with_the_minimal_payload(): void
    {
        $category = Category::factory()->create();

        $this->post('/admin/products', [
            'code' => 'MINIMO1',
            'name' => 'Producto Mínimo',
            'category_id' => $category->id,
            'stock' => 1,
        ])->assertRedirect('/admin/products');

        $this->assertDatabaseHas('products', [
            'code' => 'MINIMO1',
            'unit' => 'unidad',
            'min_stock' => 0,
            'is_active' => false,
        ]);
    }

    public function test_product_creation_validates_required_fields(): void
    {
        $this->post('/admin/products', [])->assertSessionHasErrors(['code', 'name', 'category_id']);

        $this->assertDatabaseCount('products', 0);
    }

    public function test_duplicate_code_is_rejected(): void
    {
        Product::factory()->create(['code' => 'DUP1']);
        $category = Category::factory()->create();

        $this->post('/admin/products', [
            'code' => 'DUP1',
            'name' => 'Otro',
            'category_id' => $category->id,
            'stock' => 1,
        ])->assertSessionHasErrors('code');
    }

    public function test_admin_can_update_stock_and_it_records_a_movement(): void
    {
        $product = Product::factory()->create(['stock' => 5]);

        $this->patch("/admin/products/{$product->id}/stock", [
            'field' => 'stock',
            'value' => 12,
        ])->assertRedirect();

        $this->assertSame(12, $product->fresh()->stock);
        $this->assertDatabaseHas('stock_movements', [
            'product_id' => $product->id,
            'field' => 'stock',
            'previous_value' => '5',
            'new_value' => '12',
        ]);
    }

    public function test_stock_update_rejects_unknown_field(): void
    {
        $product = Product::factory()->create();

        $this->patch("/admin/products/{$product->id}/stock", [
            'field' => 'views',
            'value' => 999,
        ])->assertSessionHasErrors('field');
    }

    public function test_csv_import_creates_and_updates_products(): void
    {
        $category = Category::factory()->create(['slug' => 'plomeria']);

        Product::factory()->create(['code' => 'EXISTE', 'name' => 'Nombre viejo']);

        $csv = "code,name,description,brand,categoria,price,stock_total,stock_apartado,stock_vendido,is_active,is_featured\n"
            ."NUEVO1,Producto del CSV,,Bosch,plomeria,15.50,7,0,0,1,0\n"
            ."EXISTE,Nombre nuevo,,Bosch,plomeria,20,3,0,0,1,0\n";

        $this->post('/admin/products/import', [
            'file' => UploadedFile::fake()->createWithContent('productos.csv', $csv),
        ])->assertRedirect();

        $this->assertDatabaseHas('products', ['code' => 'NUEVO1', 'name' => 'Producto del CSV', 'stock' => 7]);
        $this->assertDatabaseHas('products', ['code' => 'EXISTE', 'name' => 'Nombre nuevo', 'stock' => 3]);
    }

    public function test_csv_import_requires_code_column(): void
    {
        $csv = "nombre,precio\nAlgo,10\n";

        $this->post('/admin/products/import', [
            'file' => UploadedFile::fake()->createWithContent('malo.csv', $csv),
        ])->assertSessionHas('error');
    }

    public function test_admin_can_delete_a_product(): void
    {
        $product = Product::factory()->create();

        $this->delete("/admin/products/{$product->id}")->assertRedirect('/admin/products');

        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    public function test_stock_movement_keeps_previous_and_new_values(): void
    {
        $product = Product::factory()->create(['stock' => 10]);

        $this->patch("/admin/products/{$product->id}/stock", ['field' => 'stock', 'value' => 10]);

        $this->assertSame(0, StockMovement::count());
    }
}
