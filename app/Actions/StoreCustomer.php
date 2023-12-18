<?php

namespace App\Actions;

use App\Http\Requests\StoreCustomerRequest;
use App\Models\Address;
use App\Models\Contact;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StoreCustomer
{
    public function handle(StoreCustomerRequest $request)
    {
        DB::transaction(function() use ($request) {
            $validated = $request->validated();
            $sameAsShippingAddress = $validated['shippingAddress'] === $validated['billingAddress'];
            
            $customer = new Customer;
            $customer->name = $validated['name'];
            $customer->slug = Str::slug($validated['name']);
            $customer->email = $validated['email'];
            $customer->phone = $validated['phone'];
            $customer->tax = $validated['tax_number'];
            $customer->kvk = $validated['kvk_number'];

            $shippingAddress = new Address;
            $shippingAddress->address = $validated['shippingAddress']['address'];
            $shippingAddress->zipcode = $validated['shippingAddress']['zipcode'];
            $shippingAddress->city = $validated['shippingAddress']['city'];
            $shippingAddress->state = $validated['shippingAddress']['state'];
            $shippingAddress->country = $validated['shippingAddress']['country'];

            $shippingAddress->save();

            $billingAddress = null;
            if ($sameAsShippingAddress) {
                $billingAddress = $shippingAddress;
            } else {
                $billingAddress = new Address;
                $billingAddress->address = $validated['billingAddress']['address'];
                $billingAddress->zipcode = $validated['billingAddress']['zipcode'];
                $billingAddress->city = $validated['billingAddress']['city'];
                $billingAddress->state = $validated['billingAddress']['state'];
                $billingAddress->country = $validated['billingAddress']['country'];
            }
            $billingAddress->save();

            $customer->shippingAddress()->associate($shippingAddress);
            $customer->billingAddress()->associate($billingAddress);

            $customer->save();

            foreach($validated['contacts'] as $contact) {
                $contact['customer_id'] = $customer->id;
                Contact::create($contact);
            }
        });
    }
}