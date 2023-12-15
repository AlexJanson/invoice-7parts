<?php

namespace App\Http\Controllers;

use App\Actions\StorePayment;
use App\Http\Requests\StorePaymentRequest;
use App\Models\Customer;
use App\Models\Payment;
use App\Traits\WithSort;
use Illuminate\Http\Request;

class PaymentsController extends Controller
{
    use WithSort;

    public function index() 
    {
        $this->validateSort(['date', 'customer', 'invoice-number', 'amount']);

        $query = Payment::query();
        $this->sortByColumns(['date' => 'payment_date', 'customer' => 'name', 'invoice-number' => 'invoice_number'], [], $query);

        $query->select('payments.*', 'invoices.invoice_number', 'customers.slug', 'customers.name')
            ->join('customers', 'payments.customer_id', '=', 'customers.id')
            ->join('invoices', 'payments.invoice_id', '=', 'invoices.id');

        if (request('search'))
            $query->where('invoices.invoice_number', 'LIKE', '%'.request('search').'%')
                    ->orWhere('customers.name', 'LIKE', '%'.request('search').'%');
        
        $payments = $query->get();

        return inertia('Payments/Index', [
            'payments' => $payments->paginate(Payment::PAGINATE_AMOUNT)
        ]);
    }

    public function create()
    {
        return $this->index()
            ->with([
                'customers' => Customer::all()
            ]);
    }

    public function show(Payment $payment)
    {
        return inertia('Payments/Show', [
            'payment' => $payment->load(['invoice:id,invoice_number', 'customer:id,name'])
        ]);
    }

    public function edit(Payment $payment)
    {
        return $this->index()
            ->with([
                'payment' => $payment->load(['invoice:id,invoice_number', 'customer:id,name']),
                'customers' => Customer::all()
            ]);
    }

    public function update(StorePaymentRequest $request, Payment $payment)
    {
        dd($request->validated());
        $payment->update(request()->all());
    }

    public function store(StorePaymentRequest $request, StorePayment $storePayment)
    {
        $storePayment->handle($request);
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();
        return back();
    }
}
