<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->words(3, true);
        $code = strtoupper(fake()->unique()->bothify('??##-???'));

        return [
            'code' => $code,
            'name' => Str::title($name),
            'slug' => Str::slug($name).'-'.Str::lower($code),
            'short_description' => fake()->sentence(8),
            'description' => fake()->paragraph(),
            'brand' => fake()->randomElement(['Bosch', 'Stanley', 'Sika', 'Nissan', 'DeWalt']),
            'manufacturer' => fake()->company(),
            'price' => fake()->randomFloat(2, 10, 5000),
            'unit' => fake()->randomElement(['unidad', 'metro', 'rollo', 'caja']),
            'stock' => fake()->numberBetween(0, 100),
            'min_stock' => 5,
            'stock_apartado' => 0,
            'stock_vendido' => 0,
            'is_active' => true,
            'is_featured' => false,
            'images' => [],
            'variants' => [],
            'views' => 0,
            'sort_order' => 0,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => ['is_active' => false]);
    }

    public function featured(): static
    {
        return $this->state(fn (array $attributes) => ['is_featured' => true]);
    }

    /**
     * Si la categoría es hija, el producto queda en su categoría padre y
     * con esa subcategoría; si es raíz, solo con la categoría.
     */
    public function inCategory(Category $category): static
    {
        return $this->state(fn (array $attributes) => $category->parent_id
            ? ['category_id' => $category->parent_id, 'subcategory_id' => $category->id]
            : ['category_id' => $category->id, 'subcategory_id' => null]);
    }

    public function outOfStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'stock' => 0,
            'stock_apartado' => 0,
            'stock_vendido' => 0,
        ]);
    }
}
