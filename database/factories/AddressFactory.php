<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Address>
 */
class AddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'address' => $this->faker->streetAddress,
            'zipcode' => $this->faker->postcode,
            'city' => $this->faker->city,
            'state' => null,
            'country' => $this->faker->randomElement(['The Netherlands', 'United States of America', 'United Kingdom', 'France'])
        ];
    }
}
