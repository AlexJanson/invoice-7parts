<?php

namespace App\Http\Controllers;

use App\Actions\StorePayment;
use App\Http\Requests\StorePaymentRequest;
use App\Models\Payment;

class PaymentsController extends Controller
{
    public function show(Payment $payment)
    {
        return inertia('Payments/Show', [
            'payment' => $payment->load(['invoice:id,invoice_number', 'customer:id,name'])
        ]);
    }

    public function store(StorePaymentRequest $request, StorePayment $storePayment)
    {
        $storePayment->handle($request);
        
        return to_route('invoices.index');
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();

        return back();
    }
}
