<?php

namespace Tests\Unit;

use App\Models\Product;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class ProductStockTest extends TestCase
{
    /**
     * @return array<string, array{int, int, int, int}>
     */
    public static function stockScenarios(): array
    {
        return [
            'sin apartados' => [10, 0, 0, 10],
            'con apartado' => [10, 3, 0, 7],
            'con venta' => [10, 0, 4, 6],
            'con apartado y venta' => [10, 3, 4, 3],
            'sobrevendido nunca es negativo' => [2, 5, 0, 0],
        ];
    }

    #[DataProvider('stockScenarios')]
    public function test_stock_disponible_never_goes_negative(int $stock, int $apartado, int $vendido, int $expected): void
    {
        $product = new Product([
            'stock' => $stock,
            'stock_apartado' => $apartado,
            'stock_vendido' => $vendido,
        ]);

        $this->assertSame($expected, $product->stock_disponible);
    }

    public function test_in_stock_reflects_available_stock(): void
    {
        $withStock = new Product(['stock' => 5, 'stock_apartado' => 0, 'stock_vendido' => 0]);
        $withoutStock = new Product(['stock' => 5, 'stock_apartado' => 5, 'stock_vendido' => 0]);

        $this->assertTrue($withStock->in_stock);
        $this->assertFalse($withoutStock->in_stock);
    }

    public function test_main_image_is_the_first_image(): void
    {
        $product = new Product(['images' => ['products/a.webp', 'products/b.webp']]);

        $this->assertSame('products/a.webp', $product->main_image);
    }

    public function test_main_image_is_null_without_images(): void
    {
        $this->assertNull((new Product(['images' => []]))->main_image);
    }

    public function test_price_formatted_falls_back_to_consultar(): void
    {
        $this->assertSame('Consultar', (new Product(['price' => null]))->price_formatted);
        $this->assertSame('Bs 25.50', (new Product(['price' => 25.5]))->price_formatted);
    }
}
