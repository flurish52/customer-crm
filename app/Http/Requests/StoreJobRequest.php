<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreJobRequest extends FormRequest
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
            'customer_id' => 'required|exists:customers,id',
            'job_title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,in_progress,completed,cancelled,in_progress',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date|after_or_equal:today',
            'satisfaction_score' => 'nullable|integer|between:1,5',
            'completed_at' => ['nullable', 'date'],
            'completedExtras.amount_paid' => 'nullable|required_if:status,completed|numeric|min:0',
            'completedExtras.satisfaction' => 'nullable|required_if:status,completed|integer|between:1,5',
            'completedExtras.payment_method' => 'required_if:status,completed|string',
        ];
    }
}
