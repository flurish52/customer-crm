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
            'start_date' => 'required|date',
            'due_date' => 'required|date',
            'completed_at' => 'nullable|date',
        ];

    }
}
