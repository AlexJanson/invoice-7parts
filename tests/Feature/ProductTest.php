<?php

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('can create a product', function () {
    $product = Product::factory()->create();

    $this->assertDatabaseCount(Product::class, 1);
    $this->assertModelExists($product);
});

test('cannot create a product with invalid data', function () {
    Product::factory()->create([
        'name' => null
    ]);
})->throws(\Illuminate\Database\QueryException::class);

test('calculates tax correctly', function () {
    $product = Product::factory()->create([
        'price' => 1000,
        'tax' => 21
    ]);

    expect($product->price_incl_tax)->toEqual(1210);
});
