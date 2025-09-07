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
            'job_id' => 'required|exists:customer_jobs,id',
            'job_description' => 'nullable|string|max:255',
            'status' => 'required|string|in:unpaid,paid,partially_paid,cancelled',
            'issue_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:issue_date',

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
            'currency'=>'required|string|Max:255'
        ];

    }
}
