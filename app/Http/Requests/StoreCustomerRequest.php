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
            "kvk_number" => "nullable|size:8"
        ];
    }
}
