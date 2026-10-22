<?php

namespace Database\Factories;

use App\Models\Tip;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Tip>
 */
class TipFactory extends Factory
{
    protected $model = Tip::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = Str::title(fake()->unique()->words(4, true));

        return [
            'title' => $title,
            'slug' => Str::slug($title).'-'.fake()->unique()->numberBetween(1, 99999),
            'category' => fake()->randomElement(['Electricidad', 'Plomería', 'Ferretería', 'Construcción']),
            'excerpt' => fake()->sentence(12),
            'content' => '<p>'.fake()->paragraph().'</p>',
            'image' => null,
            'read_time' => fake()->numberBetween(2, 15),
            'is_published' => true,
            'is_featured' => false,
            'related_products' => [],
            'published_at' => now()->subDays(fake()->numberBetween(0, 60)),
        ];
    }

    public function draft(): static
    {
        return $this->state(fn (array $attributes) => ['is_published' => false]);
    }

    public function featured(): static
    {
        return $this->state(fn (array $attributes) => ['is_featured' => true]);
    }
}
