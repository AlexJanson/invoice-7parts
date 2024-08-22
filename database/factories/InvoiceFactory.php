<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invoice>
 */
class InvoiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $lastInvoiceNumberFromDatabase = Invoice::withTrashed()
            ->where('invoice_number', 'like', date('Y') . '%')
            ->max('invoice_number');
        if ($lastInvoiceNumberFromDatabase)
            $lastInvoiceNumberFromDatabase = strval(++$lastInvoiceNumberFromDatabase);
        $invoiceNumber = $lastInvoiceNumberFromDatabase ?? date('Y') . '0001';

        $invoiceDate = $this->faker->dateTimeThisYear;

        return [
            'invoice_number' => $invoiceNumber,
            'invoice_date' => $invoiceDate,
            'due_date' => $invoiceDate->modify('+2 week'),
            'reference' => $this->faker->lexify('?????'),
            'term' => $this->faker->monthName(),
            'year' => date('Y'),
            'status' => $this->faker->randomElement(['DRAFT', 'SENT', 'COMPLETED']),
            'payment_status' => $this->faker->randomElement(['UNPAID', 'PARTIALLY PAID', 'PAID']),
            'discount_type' => 'FIXED',
            'discount' => 0,
            'comments' => $this->faker->paragraph,
            'customer_id' => Customer::factory(),
            'contact_id' => null,
        ];
    }

    public function withItems(int $count = 1): static
    {
        return $this->afterCreating(function (Invoice $invoice) use ($count) {
            $invoice->items()->saveMany(InvoiceItem::factory()->count($count)->make([
                'invoice_id' => $invoice->id
            ]));
        });
    }

    public function withPayments(int $count = 1): static
    {
        return $this->afterCreating(function (Invoice $invoice) use ($count) {
            $invoice->payments()->saveMany(Payment::factory()->count($count)->make([
                'invoice_id' => $invoice->id
            ]));
        });
    }
}
