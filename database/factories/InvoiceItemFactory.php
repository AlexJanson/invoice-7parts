<?php

namespace Database\Factories;

use App\Models\Invoice;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\InvoiceItem>
 */
class InvoiceItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => implode(' ', $this->faker->words(2)),
            'description' => $this->faker->paragraph,
            'quantity' => $this->faker->numberBetween(100, 1_000),
            'unit' => $this->faker->randomElement(['HOURS', 'PIECES']),
            'price' => $this->faker->numberBetween(100, 1_000),
            'tax' => $this->faker->randomNumber([0, 9, 21]),
            'invoice_id' => Invoice::factory(),
            'discount' => $this->faker->numberBetween(0, 10)
        ];
    }
}
