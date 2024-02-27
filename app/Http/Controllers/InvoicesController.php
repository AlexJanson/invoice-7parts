<?php

namespace App\Http\Controllers;

use App\Actions\StoreInvoice;
use App\Actions\UpdateInvoice;
use App\Http\Requests\StoreInvoiceRequest;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Product;
use App\Traits\WithSort;
use Barryvdh\DomPDF\Facade\Pdf;

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
        $lastInvoiceNumberFromDatabase = Invoice::
            withTrashed()
            ->where('invoice_number', 'like', date('Y') . '%')
            ->max('invoice_number');
        if ($lastInvoiceNumberFromDatabase)
            $lastInvoiceNumberFromDatabase = strval(++$lastInvoiceNumberFromDatabase);
        $invoiceNumber = $lastInvoiceNumberFromDatabase ?? date('Y') . '0001';

        return $this->index()
            ->with([
                'customers' => Customer::all(),
                'products' => Product::all(),
                'invoiceNumber' => $invoiceNumber
            ]);
    }

    public function show(Invoice $invoice)
    {
        return inertia('Invoices/Show', [
            'invoice' => $invoice
                ->load(['customer', 'customer.shippingAddress', 'customer.contacts', 'items'])
                ->append(['total', 'subtotal', 'tax'])
        ]);
    }

    public function edit(Invoice $invoice)
    {
        return $this->index()
            ->with([
                'invoice' => $invoice->load(['items', 'customer', 'contact'])->append(['total', 'subtotal', 'tax']),
                'customers' => Customer::all(),
                'products' => Product::all(),
                'payments' => $invoice->payments
            ]);
    }

    public function update(StoreInvoiceRequest $request, Invoice $invoice, UpdateInvoice $updateInvoice)
    {
        $updateInvoice->handle($request, $invoice);

        return to_route('invoices.index');
    }

    public function store(StoreInvoiceRequest $request, StoreInvoice $storeInvoice)
    {
        $storeInvoice->handle($request);

        return to_route('invoices.index');
    }

    public function restore(Invoice $invoice)
    {
        $invoice->restore();
        return back();
    }

    public function destroy(Invoice $invoice)
    {
        $invoice->delete();
        return back();
    }

    public function createPayment(Invoice $invoice)
    {
        return $this->index()->with([
            'invoice' => $invoice,
            'customers' => Customer::all()
        ]);
    }

    public function download(Invoice $invoice)
    {
        $pdf = Pdf::loadView('pdf.invoice', [
            "invoice" => $invoice->load(['customer.shippingAddress']),
            "noAddress" => null
        ]);
        return $pdf->download('Factuur ' . $invoice->invoice_number . ' ' . $invoice->customer->name . '.pdf');
    }

    public function stream(Invoice $invoice)
    {
        $pdf = Pdf::loadView('pdf.invoice', [
            "invoice" => $invoice->load(['customer.shippingAddress']),
            "noAddress" => null
        ]);
        return $pdf->stream();
    }
}
