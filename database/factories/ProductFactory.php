<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = implode(' ', $this->faker->words(2));
        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'price' => $this->faker->numberBetween(1, 1_000_000),
            'tax' => $this->faker->randomElement([0, 9, 21]),
            'unit' => $this->faker->randomElement(['HOURS', 'PIECES']),
            'description' => $this->faker->paragraph
        ];
    }
}
