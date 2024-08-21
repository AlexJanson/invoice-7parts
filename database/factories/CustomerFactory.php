<?php

namespace Database\Factories;

use App\Models\Address;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Faker\Provider\nl_NL\Company;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $this->faker->addProvider(new Company($this->faker));
        $name = $this->faker->name;

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'email' => $this->faker->email,
            'phone' => $this->faker->phoneNumber,
            'tax' => $this->faker->vat,
            'kvk' => $this->faker->btw,
            'shipping_address' => Address::factory(),
            'billing_address' => Address::factory(),
        ];
    }
}
