<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateBusinessRequest extends FormRequest
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
        $userId = Auth::id();
        $business = $this->route('business');
        $businessId = $business->id;
        return [
        'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('businesses', 'business_email')
                    ->where(function ($query) use ($userId) {
                        return $query->where('user_id', '!=', $userId);
                    })
                    ->ignore($businessId),
            ],
        'phone' => 'nullable|string|max:20',
        'address' => 'nullable|string|max:500',
        'website' => 'nullable|url|max:255',
        'logo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        'tax_id' => 'nullable|string|max:100',
        'settings' => 'nullable|json'
        ];
    }
}
