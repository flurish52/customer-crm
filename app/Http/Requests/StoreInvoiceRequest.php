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
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'business_id' => 'required|exists:businesses,id',
            'customer_id' => 'required|exists:customers,id',
            'job_id' => 'nullable|exists:customer_jobs,id',
            'job_description' => 'required|string|max:255',

            'items' => 'required|array|min:1',
            'items.*.name' => 'required|string|max:255',
            'items.*.description' => 'nullable|string|max:500',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',

            'total' => 'required|array',
            'total.subtotal' => 'required|numeric|min:0',
            'total.discount' => 'nullable|numeric|min:0',
            'total.vat' => 'required|numeric|min:0',
            'total.total' => 'required|numeric|min:0',
        ];

    }
}
