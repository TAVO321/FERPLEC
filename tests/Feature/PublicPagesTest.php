<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Tip;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    use RefreshDatabase;

    public static function publicRoutes(): array
    {
        return [
            'home' => ['/'],
            'catalogo' => ['/productos'],
            'soluciones' => ['/soluciones'],
            'tips' => ['/tips-ferplec'],
            'promociones' => ['/promociones'],
            'carrito' => ['/mi-lista'],
            'contacto' => ['/contacto'],
            'sitemap' => ['/sitemap.xml'],
            'robots' => ['/robots.txt'],
        ];
    }

    #[DataProvider('publicRoutes')]
    public function test_public_pages_respond_successfully(string $url): void
    {
        $this->get($url)->assertOk();
    }

    public function test_product_detail_page_is_reachable(): void
    {
        $product = Product::factory()->create(['name' => 'Tubería PVC E-40']);

        $this->get(route('product.show', $product))
            ->assertOk()
            ->assertSee('Tubería PVC E-40');
    }

    public function test_inactive_product_returns_404(): void
    {
        $product = Product::factory()->inactive()->create();

        $this->get(route('product.show', $product))->assertNotFound();
    }

    public function test_product_view_counter_increments(): void
    {
        $product = Product::factory()->create(['views' => 0]);

        $this->get(route('product.show', $product))->assertOk();

        $this->assertSame(1, $product->fresh()->views);
    }

    public function test_tip_detail_page_is_reachable(): void
    {
        $tip = Tip::factory()->create(['title' => 'Cómo instalar una grifería']);

        $this->get(route('tips.show', $tip))
            ->assertOk()
            ->assertSee('Cómo instalar una grifería');
    }

    public function test_draft_tip_returns_404(): void
    {
        $tip = Tip::factory()->draft()->create();

        $this->get(route('tips.show', $tip))->assertNotFound();
    }

    public function test_tips_can_be_filtered_by_category(): void
    {
        Tip::factory()->create(['title' => 'Guide Eléctrica', 'category' => 'Electricidad']);
        Tip::factory()->create(['title' => 'Guide Plomería', 'category' => 'Plomería']);

        $this->get('/tips-ferplec?categoria=Electricidad')
            ->assertOk()
            ->assertSee('Guide Eléctrica')
            ->assertDontSee('Guide Plomería');
    }

    public function test_home_shows_featured_categories(): void
    {
        Category::factory()->featured()->create(['name' => 'Plomería FerPLEC']);

        $this->get('/')
            ->assertOk()
            ->assertSee('Plomería FerPLEC');
    }
}
