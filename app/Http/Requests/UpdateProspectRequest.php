<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProspectRequest extends FormRequest
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
    public function rules()
    {
        $prospectId = $this->route('prospect'); // if route is /prospect/{prospect}
        return [
            'name' => 'required|string|max:255',
            'email' => [
                'nullable',
                'email',
                'required_without:phone',
                Rule::unique('prospects')
                    ->ignore($prospectId)
                    ->where(fn ($query) => $query
                        ->where('user_id', auth()->id())
                        ->whereNull('deleted_at')
                    ),
            ],
            'phone' => [
                'nullable',
                'string',
                'required_without:email',
                Rule::unique('prospects')
                    ->ignore($prospectId)
                    ->where(fn ($query) => $query
                        ->where('user_id', auth()->id())
                        ->whereNull('deleted_at')
                    ),
            ],
            'company' => 'nullable|string|max:255',
            'source' => 'nullable|string|max:100',
            'customSource' => 'nullable|string|max:100',
            'status' => 'required|string|in:new,contacted,follow_up,converted,lost',
        ];
    }

}
