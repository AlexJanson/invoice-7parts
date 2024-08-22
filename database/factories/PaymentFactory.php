<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Log;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'payment_date' => $this->faker->dateTimeThisYear,
            'amount' => $this->faker->numberBetween(1, 100),
            'comments' => $this->faker->paragraph,
            'invoice_id' => Invoice::factory()->withItems(2),
            'customer_id' => Customer::factory()
        ];
    }
}
