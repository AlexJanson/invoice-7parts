<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Product;
use App\Traits\WithSort;
use Illuminate\Http\Request;

class InvoicesController extends Controller
{
    use WithSort;

    public function index() 
    {
        $this->validateSort(['date', 'invoice-number', 'customer', 'paid', 'due-amount', 'total']);

        $query = Invoice::query();
        $column = $this->sortByColumns(['date' => 'invoice_date', 'invoice-number' => 'invoice_number', 'customer' => 'name', 'paid' => 'payment_status', 'due-amount' => 'due_amount'], ['payment_status', 'due_amount', 'total'], $query);

        $query->select(['invoices.*', 'customers.slug', 'customers.name'])->join('customers', 'invoices.customer_id', '=', 'customers.id');

        if (request('search'))
            $query->where('invoice_number', 'LIKE', '%'.request('search').'%')
                ->orWhere('customers.name', 'LIKE', '%'.request('search').'%')
                ->orWhere('payment_status', 'LIKE', '%'.request('search').'%');

        $invoices = $this->appendAttributes(['total', 'due_amount'], $query);
        $invoices = $this->sortByAttributes($invoices, $column);

        return inertia('Invoices/Index', [
            'invoices' => $invoices->values()->paginate(Invoice::PAGINATE_AMOUNT)
        ]);
    }

    public function create()
    {
        return $this->index()
            ->with([
                'customers' => Customer::all(),
                'products' => Product::all()
            ]);
    }

    public function show(Invoice $invoice)
    {
        return inertia('Invoices/Show', [
            'invoice' => $invoice
                ->load(['customer', 'customer.shippingAddress', 'customer.mainContact', 'items'])
                ->append(['total', 'subtotal', 'tax'])
        ]);
    }

    public function edit(Invoice $invoice)
    {
        return $this->index()
            ->with([
                'invoice' => $invoice->load(['items', 'customer', 'contact']),
                'customers' => Customer::all(),
                'products' => Product::all()
            ]);
    }

    public function store(/* StoreInvoiceRequest $request, StoreInvoice $storeInvoice */)
    {
        // dd($request)
    }

    public function destroy(Invoice $invoice)
    {
        $invoice->delete();
        return back();
    }
}
