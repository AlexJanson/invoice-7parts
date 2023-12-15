<?php

namespace App\Actions;

use App\Http\Requests\StoreCustomerRequest;

class StoreCustomer
{
    public function handle(StoreCustomerRequest $request)
    {
        dd($request);
    }
}