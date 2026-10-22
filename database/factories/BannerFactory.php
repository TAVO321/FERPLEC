<?php

namespace Database\Factories;

use App\Models\Banner;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Banner>
 */
class BannerFactory extends Factory
{
    protected $model = Banner::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(4),
            'subtitle' => fake()->sentence(8),
            'description' => fake()->sentence(12),
            'image' => 'banners/'.fake()->slug(2).'.webp',
            'image_mobile' => null,
            'button_text' => 'Ver catálogo',
            'button_link' => '/productos',
            'position' => fake()->randomElement(['home_hero', 'home_promo', 'promotion', 'secondary']),
            'is_active' => true,
            'sort_order' => fake()->numberBetween(0, 10),
            'starts_at' => null,
            'ends_at' => null,
        ];
    }

    public function expired(): static
    {
        return $this->state(fn (array $attributes) => [
            'starts_at' => now()->subDays(10),
            'ends_at' => now()->subDay(),
        ]);
    }
}
