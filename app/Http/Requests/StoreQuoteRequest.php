<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreQuoteRequest extends FormRequest
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
            'client_id'   => ['required', 'exists:customers,id'],
            'business_id' => ['required', 'exists:businesses,id'],
            'job_id' => ['nullable', 'exists:customer_jobs,id'],
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'terms'       => ['required', 'string'],
            'items'       => ['required', 'array', 'min:1'],
            'items.*.name'        => ['required', 'string', 'max:255'],
            'items.*.description' => ['required', 'string'],
            'items.*.quantity'    => ['required', 'integer', 'min:1'],
            'items.*.unit_price'  => ['required', 'numeric', 'min:0'],
            'exp_date'   => ['required', 'date', 'after_or_equal:today'],
            'currency'   => ['required', 'in:USD,EUR,NGN'],
            'tax'        => ['required', 'numeric', 'min:0'],
            'discount'   => ['required', 'numeric', 'min:0'],
        ];
    }
}
