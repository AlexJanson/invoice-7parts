<?php

namespace App\Http\Controllers;

use App\Actions\StoreCustomer;
use App\Http\Requests\StoreCustomerRequest;
use App\Models\Customer;
use App\Models\Invoice;
use App\Traits\WithSort;

class CustomersController extends Controller
{
    use WithSort;

    public function index()
    {
        $this->validateSort(['name', 'phone', 'due-amount']);

        $query = Customer::query();
        $this->sortByColumns(['due-amount' => 'due_amount'], ['due_amount'], $query);

        if (request('search'))
            $query->where('customers.name', 'LIKE', '%'.request('search').'%')
                    ->orWhere('phone', 'LIKE', '%'.request('search').'%');

        $customers = $this->appendAttributes(['due_amount'], $query);
        $customers = $this->sortByAttributes($customers);
        return inertia('Customers/Index', [
            'customers' => $customers->values()->paginate(Customer::PAGINATE_AMOUNT)
        ]);
    }

    public function show(Customer $customer)
    {
        $this->validateSort(['date', 'invoice-number', 'paid', 'due-amount', 'total']);

        $query = $customer->invoices()->getQuery();
        $column = $this->sortByColumns(['date' => 'invoice_date', 'invoice-number' => 'invoice_number', 'paid' => 'payment_status', 'due-amount' => 'due_amount'], ['payment_status', 'due_amount', 'total'], $query);

        if (request('search'))
            $query->where('invoice_number', 'LIKE', '%'.request('search').'%')
                ->orWhere('payment_status', 'LIKE', '%'.request('search').'%');

        $invoices = $this->appendAttributes(['total', 'due_amount'], $query);
        $invoices = $this->sortByAttributes($invoices, $column);

        return inertia('Customers/Show', [
            'customer' => $customer->load(['shippingAddress', 'billingAddress', 'mainContact', 'otherContacts']),
            'invoices' => $invoices->values()->paginate(Invoice::PAGINATE_AMOUNT)
        ]);
    }

    public function edit(Customer $customer) 
    {
        return $this->index()
            ->with([
                'customer' => $customer->load(['shippingAddress', 'billingAddress', 'mainContact', 'otherContacts'])
            ]);
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();
        dd($customer);
    }

    public function create()
    {
        return $this->index();
    }

    public function store(StoreCustomerRequest $request, StoreCustomer $storeCustomer)
    {
        dd($request);
        $storeCustomer->handle($request);
        // dd(request()->all());
    }

    public function getInvoices(Customer $customer)
    {
        return response()->json([
            'invoices' => $customer->invoices
        ]);
    }

    public function getContacts(Customer $customer)
    {
        return response()->json([
            'contacts' => $customer->contacts
        ]);
    }
}
