<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Database\Eloquent\Factories\Factory;

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
        $invoice = Invoice::factory()->create();

        InvoiceItem::factory()->create([
            'invoice_id' => $invoice->id
        ]);

        return [
            'payment_date' => $this->faker->dateTimeThisYear,
            'amount' => $this->faker->numberBetween(1, $invoice->total),
            'comments' => $this->faker->paragraph,
            'invoice_id' => $invoice->id,
            'customer_id' => Customer::factory()
        ];
    }
}
