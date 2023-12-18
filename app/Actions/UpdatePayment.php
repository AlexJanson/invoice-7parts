<?php

namespace App\Actions;

use App\Http\Requests\StorePaymentRequest;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class UpdatePayment
{
    public function handle(StorePaymentRequest $request, Payment $payment)
    {
        DB::transaction(function() use ($request, $payment) {
            $validated = $request->validated();

            $validated["payment_date"] = Carbon::parse($validated["payment_date"])->tz('Europe/Amsterdam');
            
            $payment->update($validated);
        });
    }
}