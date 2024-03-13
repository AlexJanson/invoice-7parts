<?php

namespace App\Actions;

use App\Http\Requests\StoreInvoiceRequest;
use App\Models\Contact;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Carbon\Carbon;

class UpdateInvoice
{
    public function handle(StoreInvoiceRequest $request, Invoice $invoice)
    {
        $validated = $request->validated();

        $customer = Customer::find($validated["customer"]);
        $invoice->customer()->associate($customer);
        if (isset($validated["contact"])) {
            $contact = Contact::find($validated["contact"]);
            $invoice->contact()->associate($contact);
        }

        $invoice->invoice_date = Carbon::parse($validated["invoice_date"])->tz('Europe/Amsterdam');
        $invoice->due_date = Carbon::parse($validated["due_date"])->tz('Europe/Amsterdam');

        $invoice->reference = $validated["reference"];
        $invoice->discount = $validated["discount"];
        $invoice->comments = $validated["comments"];
        $invoice->year = $validated["year"];
        $invoice->term = $validated["term"];

        $invoice->save();

        $invoiceItemsToDelete = $invoice->items->whereNotIn('id', collect($validated["items"])->pluck("id"));
        foreach ($invoiceItemsToDelete as $item) {
            $invoiceItem = InvoiceItem::find($item["id"]);
            $invoiceItem->invoice()->dissociate();
            $invoiceItem->delete();
        }

        foreach ($validated["items"] as $item) {
            $invoiceItem = array_key_exists("id", $item)
                ? InvoiceItem::find($item["id"])
                : new InvoiceItem;

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

        $invoice->updatePaymentStatus();
        $invoice->save();
    }
}