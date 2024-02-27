<?php

namespace App\Actions;

use App\Http\Requests\StoreInvoiceRequest;
use App\Models\Contact;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class StoreInvoice
{
    public function handle(StoreInvoiceRequest $request)
    {
        DB::transaction(function() use ($request) {
            $validated = $request->validated();

            $invoice = new Invoice;
            
            $invoice->invoice_number = $validated["invoice_number"];
            $invoice->invoice_date = Carbon::parse($validated["invoice_date"])->tz('Europe/Amsterdam');
            $invoice->due_date = Carbon::parse($validated["due_date"])->tz('Europe/Amsterdam');
            $invoice->reference = $validated["reference"];
            $invoice->status = 'COMPLETED'; // TODO: some better statusses? like when its a draft, send or completed.
            $invoice->payment_status = Invoice::STATUS_UNPAID;
            $invoice->discount_type = 'FIXED'; // TODO: maybe make it possible to do percentages as discount.
            $invoice->discount = $validated["discount"];
            $invoice->comments = $validated["comments"];
            $invoice->year = $validated["year"];
            $invoice->term = $validated["term"] ?? null;

            $customer = Customer::find($validated["customer"]);
            $invoice->customer()->associate($customer);
            if (isset($validated["contact"])) {
                $contact = Contact::find($validated["contact"]);
                $invoice->contact()->associate($contact);
            }

            $invoice->save();

            foreach ($validated["items"] as $item) {
                $invoiceItem = new InvoiceItem;
                $invoiceItem->name = $item["name"];
                $invoiceItem->description = $item["description"];
                $invoiceItem->quantity = $item["quantity"];
                $invoiceItem->unit = $item["unit"];
                $invoiceItem->price = $item["price"];
                $invoiceItem->tax = $item["tax"];
                $invoiceItem->discount = $item["discount"];

                $invoiceItem->invoice()->associate($invoice);
                $invoiceItem->save();
            }
        });
    }
}