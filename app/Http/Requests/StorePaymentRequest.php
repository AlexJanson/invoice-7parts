<?php

namespace App\Http\Requests;

use App\Models\Invoice;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class StorePaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $invoiceId = request()->input('invoice_id');
        $validated = request()->validate([
            'amount' => 'numeric|min:1|required'
        ]);
        $invoice = Invoice::find($invoiceId);
        if ($validated['amount'] > $invoice->dueAmount)
            throw ValidationException::withMessages([
                'amount' => ["Cannot be higher then the due amount."]
            ]);

        return [
            'payment_date' => 'required|date',
            'amount' => 'required|min:1|numeric',
            'comments' => 'nullable|string',
            'invoice_id' => 'required|exists:invoices,id',
            'customer_id' => 'required|exists:customers,id'
        ];
    }
}
