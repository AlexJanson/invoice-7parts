<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
        return [
            'payment_date' => 'required|date',
            'amount' => 'required|min:0|numeric',
            'comments' => 'nullable|string',
            'invoice_id' => 'required|exists:invoices,id',
            'customer_id' => 'required|exists:users,id'
        ];
    }
}
