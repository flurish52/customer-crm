<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateJobRequest extends FormRequest
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
            'id' => 'required|exists:customer_jobs,id',
            'user_id' => 'required|exists:users,id',
            'customer_id' => 'required|exists:customers,id',
            'job_title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,completed,cancelled,in_progress',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'nullable|date',
            'completed_at' => 'nullable|date',
            'completedExtras.amount_paid' => 'required_if:status,completed|numeric|min:0',
            'completedExtras.satisfaction' => 'required_if:status,completed|integer|between:1,5',
            'completedExtras.payment_method' => 'required_if:status,completed|string',
        ];

    }
}
