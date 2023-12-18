<?php

namespace App\Http\Controllers;

use App\Actions\StoreProduct;
use App\Actions\UpdateProduct;
use App\Http\Requests\StoreProductRequest;
use App\Models\Product;
use App\Traits\WithSort;

class ProductsController extends Controller
{
    use WithSort;

    public function index()
    {
        $this->validateSort(['name', 'price', 'date']);

        $query = Product::query();
        $this->sortByColumns(['date' => 'created_at'], [], $query);

        if (request('search'))
            $query->where('name', 'LIKE', '%'.request('search').'%')
                    ->orWhere('price', 'LIKE', '%'.request('search').'%');

        $products = $this->sortByAttributes($query->get());

        return inertia('Products/Index', [
            'products' => $products->values()->paginate(Product::PAGINATE_AMOUNT)
        ]);
    }

    public function show(Product $product) 
    {
        return inertia('Products/Show', [
            'product' => $product
        ]);
    }

    public function edit(Product $product)
    {
        return $this->index()
            ->with([
                'product' => $product
            ]);
    }

    public function create()
    {
        return $this->index();
    }

    public function update(StoreProductRequest $request, Product $product, UpdateProduct $updateProduct)
    {
        $updateProduct->handle($request, $product);

        return to_route('products.index');
    }

    public function store(StoreProductRequest $request, StoreProduct $storeProduct)
    {
        $storeProduct->handle($request);

        return to_route('products.index');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        
        return to_route('products.index');
    }
}
