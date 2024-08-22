<?php

use App\Models\Address;
use App\Models\Customer;
use App\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('can create a customer', function () {
    $customer = Customer::factory()->create();

    $this->assertDatabaseCount(Customer::class, 1);
    $this->assertModelExists($customer);
});

test('cannot create a customer with invalid data', function () {
    $customer = Customer::factory()->create([
        'name' => null
    ]);
})->throws(\Illuminate\Database\QueryException::class);

test('can create a customer with invoices', function() {
    $customer = Customer::factory()->withInvoices(2)->create();

    expect($customer->invoices)->toHaveCount(2);
});

test('can create a customer with contacts', function () {
    $customer = Customer::factory()->withContacts(2)->create();

    expect($customer->contacts)->toHaveCount(2);
});

test('can create a customer with a different billing and shipping address', function () {
    $billingAddress = Address::factory()->create();
    $shippingAddress = Address::factory()->create();

    $customer = Customer::factory()->create([
        'billing_address' => $billingAddress->id,
        'shipping_address' => $shippingAddress->id
    ]);

    expect($customer->billingAddress->attributesToArray())->toEqual($billingAddress->attributesToArray())
        ->and($customer->shippingAddress->attributesToArray())->toEqual($shippingAddress->attributesToArray());
});

test('calculates due amount correctly', function () {
    $customer = Customer::factory()->withInvoices(3)->create();

    $customer->invoices[0]->payments()->save(Payment::factory()->create());

    expect($customer->due_amount)->toEqual($customer->invoices->sum('due_amount'));
});
