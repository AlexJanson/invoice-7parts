<?php

use App\Models\InvoiceItem;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('can create an invoice item', function () {
    $item = InvoiceItem::factory()->create();

    $this->assertDatabaseCount(InvoiceItem::class, 1);
    $this->assertModelExists($item);
});

test('cannot create an invoice item with invalid data', function () {
    $item = InvoiceItem::factory()->create([
        'invoice_id' => null
    ]);
})->throws(\Illuminate\Database\QueryException::class);

test('calculates subtotal and total correctly', function () {
    $item = InvoiceItem::factory()->create([
        'quantity' => 150,
        'price' => 500,
        'tax' => 20,
        'discount' => 5
    ]);

    expect($item->subtotal)->toEqual(750)
        ->and($item->total)->toEqual(855);
});
