<?php

namespace Database\Factories;

use App\Models\Address;
use App\Models\Contact;
use App\Models\Customer;
use App\Models\Invoice;
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

    public function withInvoices(int $count = 1): static
    {
        return $this->afterCreating(function (Customer $customer) use ($count) {
            // For the invoice number to correctly increment we need to save them
            //  to the database immediately, otherwise we get duplicate key exceptions.
            for ($i = 0; $i < $count; $i++) {
                $invoice = Invoice::factory()->withItems(2)->create([
                    'customer_id' => $customer->id
                ]);
            }
        });
    }

    public function withContacts(int $count = 2): static
    {
        return $this->afterCreating(function (Customer $customer) use ($count) {
            $customer->contacts()->saveMany(Contact::factory()->count($count)->make([
                'customer_id' => $customer->id
            ]));
        });
    }
}
