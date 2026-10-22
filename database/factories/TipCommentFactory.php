<?php

namespace Database\Factories;

use App\Models\Tip;
use App\Models\TipComment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TipComment>
 */
class TipCommentFactory extends Factory
{
    protected $model = TipComment::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tip_id' => Tip::factory(),
            'name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'content' => fake()->paragraph(),
            'is_approved' => false,
            'ip' => fake()->ipv4(),
        ];
    }

    public function approved(): static
    {
        return $this->state(fn (array $attributes) => ['is_approved' => true]);
    }
}
