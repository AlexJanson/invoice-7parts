<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInvoiceRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'customer' => 'numeric|exists:customers,id|required',
            'contact' => 'numeric|exists:contacts,id|nullable',
            'invoice_number' => 'required|string',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date',
            'reference' => 'nullable|max:50',
            'term' => 'nullable|max:50',
            'year' => 'numeric|required',
            'comments' => 'nullable|max:255',
            'discount' => 'required|numeric|min:0',
            'items.*.id' => 'nullable|numeric|exists:invoice_items,id',
            'items.*.name' => 'required|min:3|max:100',
            'items.*.price' => 'required|numeric|min:1',
            'items.*.tax' => 'required|numeric|min:0',
            'items.*.unit' => 'required|in:PIECES,HOURS',
            'items.*.description' => 'nullable|string|max:255',
            'items.*.quantity' => 'numeric|min:100|required',
            'items.*.discount' => 'numeric|min:0|max:100|nullable'
        ];
    }
}
