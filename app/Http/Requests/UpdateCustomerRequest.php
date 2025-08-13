<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomerRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'company' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255|unique:customers,email,NULL,id,user_id,',
            'phone' => 'required|string|max:20|unique:customers,phone,NULL,id,user_id,',
            'address' => 'nullable|string|max:255',
            'note' => 'nullable|string|max:255',
            'avatar.file' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'avatar' => 'nullable|string',
        ];
    }
}
