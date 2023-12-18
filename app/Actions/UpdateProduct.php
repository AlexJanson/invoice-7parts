<?php

namespace App\Actions;

use App\Http\Requests\StoreProductRequest;
use App\Models\Contact;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UpdateProduct
{
    public function handle(StoreProductRequest $request, Product $product)
    {
        DB::transaction(function() use ($request, $product) {
            $validated = $request->validated();
            $validated['slug'] = Str::slug($validated['name']);

            $product->update($validated);
        });
    }
}