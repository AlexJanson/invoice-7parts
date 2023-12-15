<?php

namespace App\Actions;

use App\Http\Requests\StorePaymentRequest;
use App\Models\Payment;
use Carbon\Carbon;

class StorePayment
{
    public function handle(StorePaymentRequest $request)
    {
        $validated = $request->validated();

        $validated["payment_date"] = Carbon::parse($validated["payment_date"])->tz('Europe/Amsterdam');

        Payment::create($validated);
    }
}