<?php

use App\Models\Invoice;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('can create an invoice', function () {
    $invoice = Invoice::factory()->create();

    $this->assertDatabaseCount(Invoice::class, 1);
    $this->assertModelExists($invoice);
});

test('cannot create an invoice with invalid data', function () {
    Invoice::factory()->create([
        'invoice_date' => null
    ]);
})->throws(\Illuminate\Database\QueryException::class);

test('can create an invoice with items', function () {
    $invoice = Invoice::factory()->withItems(2)->create();

    expect($invoice->items)->toHaveCount(2);
});

test('can create an invoice with payments', function () {
    $invoice = Invoice::factory()->withItems(2)->withPayments(2)->create();

    expect($invoice->payments)->toHaveCount(2);
});

test('calculates subtotal, tax and total correctly', function () {

    $invoice = Invoice::factory()->withItems(2)->create();

    Model::unguard(); // disable the mass assignment exceptions
    $invoice->items[0]->update([
        'quantity' => 100,
        'price' => 250,
        'tax' => 10,
        'discount' => 0
    ]);

    $invoice->items[1]->update([
        'quantity' => 200,
        'price' => 500,
        'tax' => 0,
        'discount' => 5
    ]);
    Model::unguard(false);

    expect($invoice->subtotal)->toEqual(1200)
        ->and($invoice->tax)->toEqual(25)
        ->and($invoice->total)->toEqual(1225);
});

test('calculates due amount correctly', function () {
    $invoice = Invoice::factory()->withItems(2)->withPayments(2)->create();

    Model::unguard(); // disable the mass assignment exceptions
    $invoice->items[0]->update([
        'quantity' => 200,
        'price' => 250,
        'tax' => 10,
        'discount' => 0
    ]);

    $invoice->items[1]->update([
        'quantity' => 200,
        'price' => 1000,
        'tax' => 0,
        'discount' => 5
    ]);
    Model::unguard(false);

    $invoice->payments[0]->update(['amount' => 500]);
    $invoice->payments[1]->update(['amount' => 1000]);

    expect($invoice->due_amount)->toEqual(950);
});

test('updates payment status correctly when unpaid', function () {
    $invoice = Invoice::factory()->withItems()->create();

    $invoice->updatePaymentStatus();
    expect($invoice->payment_status)->toEqual(Invoice::STATUS_UNPAID);
});

test('updates payment status correctly when partially paid', function () {
    $invoice = Invoice::factory()->withItems(2)->withPayments()->create();
    $invoice->payments[0]->update(['amount' => 1]);

    $invoice->updatePaymentStatus();
    expect($invoice->payment_status)->toEqual(Invoice::STATUS_PARTIALLY_PAID);
});

test('updates payment status correctly when fully paid', function () {
    $invoice = Invoice::factory()->withItems()->withPayments()->create();
    $invoice->payments[0]->update(['amount' => $invoice->total]);

    $invoice->updatePaymentStatus();
    expect($invoice->payment_status)->toEqual(Invoice::STATUS_PAID);
});

test('retrieves due invoices correctly', function () {
    $dueInvoice = Invoice::factory()->withItems(2)->create([
        'due_date' => now()->subDays(2),
    ]);
    $dueInvoice->updatePaymentStatus();

    $notDueInvoice = Invoice::factory()->withItems()->create([
        'due_date' => now()->addDays(2)
    ]);

    $dueInvoiceButPaid = Invoice::factory()->withPayments()->withItems()->create([
        'due_date' => now()->subDays(2),
    ]);
    $dueInvoiceButPaid->payments[0]->update(['amount' => $dueInvoiceButPaid->total]);

    $dueInvoiceButPartiallyPaid = Invoice::factory()->withPayments()->withItems()->create([
        'due_date' => now()->subDays(2),
    ]);
    $dueInvoiceButPartiallyPaid->payments[0]->update(['amount' => $dueInvoiceButPartiallyPaid->total - 1]);

    $dueInvoices = collect(Invoice::dueInvoices()->get())->pluck('invoice_number');

    expect($dueInvoices)
        ->toContain($dueInvoice->invoice_number)
        ->toContain($dueInvoiceButPartiallyPaid->invoice_number)
        ->not->toContain($notDueInvoice->invoice_number)
        ->not->toContain($dueInvoiceButPaid->invoice_number);
});
