<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCustomerRequest extends FormRequest
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
            "name" => "required|min:3|max:50",
            "email" => "required|max:50|email",
            "phone" => "required|min:8|max:25",
            "tax_number" => "required|max:20",
            "kvk_number" => "nullable|size:8",
            "shippingAddress.address" => "required|max:50",
            "shippingAddress.zipcode" => "required|max:10",
            "shippingAddress.city" => "required|max:30",
            "shippingAddress.state" => "nullable|max:35",
            "shippingAddress.country" => "required|max:35",
            "billingAddress.address" => "required|max:50",
            "billingAddress.zipcode" => "required|max:10",
            "billingAddress.city" => "required|max:30",
            "billingAddress.state" => "nullable|max:35",
            "billingAddress.country" => "required|max:35",
            "contacts.*.id" => "nullable|numeric",
            "contacts.*.name" => "required|min:3|max:50",
            "contacts.*.email" => "required|max:50|email",
            "contacts.*.phone" => "required|min:8|max:25",
            "contacts.*.default" => "required|boolean"
        ];
     }
}
