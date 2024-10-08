<?php

namespace App\Actions;

use App\Http\Requests\StoreCustomerRequest;
use App\Models\Contact;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UpdateCustomer
{
    public function handle(StoreCustomerRequest $request, Customer $customer)
    {
        DB::transaction(function() use ($request, $customer) {
            $validated = $request->validated();
            $sameAsShippingAddress = $validated['shippingAddress'] === $validated['billingAddress'];
            
            $customer->name = $validated['name'];
            $customer->slug = Str::slug($validated['name']);
            $customer->email = $validated['email'];
            $customer->phone = $validated['phone'];
            $customer->tax = $validated['tax_number'] ?? null;
            $customer->kvk = $validated['kvk_number'];

            $customer->save();

            $customer->shippingAddress->address = $validated['shippingAddress']['address'];
            $customer->shippingAddress->zipcode = $validated['shippingAddress']['zipcode'];
            $customer->shippingAddress->city = $validated['shippingAddress']['city'];
            $customer->shippingAddress->state = $validated['shippingAddress']['state'];
            $customer->shippingAddress->country = $validated['shippingAddress']['country'];

            $customer->shippingAddress->save();

            if (!$sameAsShippingAddress) {
                $customer->billingAddress->address = $validated['billingAddress']['address'];
                $customer->billingAddress->zipcode = $validated['billingAddress']['zipcode'];
                $customer->billingAddress->city = $validated['billingAddress']['city'];
                $customer->billingAddress->state = $validated['billingAddress']['state'];
                $customer->billingAddress->country = $validated['billingAddress']['country'];
            }
            $customer->billingAddress->save();

            $contactsToDelete = $customer->contacts->whereNotIn('id', collect($validated["contacts"] ?? [])->pluck("id"));
            foreach ($contactsToDelete as $item) {
                $contact = Contact::find($item["id"]);
                $contact->customer()->dissociate();
                $contact->delete();
            }

            if (isset($validated['contacts'])) {
                foreach($validated['contacts'] as $contact) {
                    $dbContact = array_key_exists("id", $contact) 
                        ? Contact::find($contact['id']) 
                        : new Contact;
                    $dbContact->name = $contact['name'];
                    $dbContact->email = $contact['email'];
                    $dbContact->phone = $contact['phone'];

                    $dbContact->customer()->associate($customer);

                    $dbContact->save();
                }
            }
        });
    }
}