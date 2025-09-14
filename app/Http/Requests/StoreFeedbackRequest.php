<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFeedbackRequest extends FormRequest
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
        'user_id' => 'nullable|exists:users,id',
        'modules' => 'nullable|array|required_without_all:features,frequency,navigation,speed,confusing,missing_features,satisfaction,recommend,suggestions,business_type,team_size',
        'modules.*' => 'string',
        'features' => 'nullable|array|required_without_all:modules,frequency,navigation,speed,confusing,missing_features,satisfaction,recommend,suggestions,business_type,team_size',
        'features.*' => 'string',
        'frequency' => 'nullable|string|in:Daily,Weekly,Monthly,Rarely|required_without_all:modules,features,navigation,speed,confusing,missing_features,satisfaction,recommend,suggestions,business_type,team_size',
        'navigation' => 'nullable|integer|min:1|max:5|required_without_all:modules,features,frequency,speed,confusing,missing_features,satisfaction,recommend,suggestions,business_type,team_size',
        'speed' => 'nullable|integer|min:1|max:5|required_without_all:modules,features,frequency,navigation,confusing,missing_features,satisfaction,recommend,suggestions,business_type,team_size',
        'confusing' => 'nullable|string|max:1000|required_without_all:modules,features,frequency,navigation,speed,missing_features,satisfaction,recommend,suggestions,business_type,team_size',
        'missing_features' => 'nullable|string|max:1000|required_without_all:modules,features,frequency,navigation,speed,confusing,satisfaction,recommend,suggestions,business_type,team_size',
        'satisfaction' => 'nullable|integer|min:1|max:5|required_without_all:modules,features,frequency,navigation,speed,confusing,missing_features,recommend,suggestions,business_type,team_size',
        'recommend' => 'nullable|integer|min:1|max:5|required_without_all:modules,features,frequency,navigation,speed,confusing,missing_features,satisfaction,suggestions,business_type,team_size',
        'suggestions' => 'nullable|string|max:2000|required_without_all:modules,features,frequency,navigation,speed,confusing,missing_features,satisfaction,recommend,business_type,team_size',
        'business_type' => 'nullable|string|in:Artisan,Freelancer,Small Service Business|required_without_all:modules,features,frequency,navigation,speed,confusing,missing_features,satisfaction,recommend,suggestions,team_size',
        'team_size' => 'nullable|string|in:Solo,2-5,6-10,10+|required_without_all:modules,features,frequency,navigation,speed,confusing,missing_features,satisfaction,recommend,suggestions,business_type',       ];
    }
}
