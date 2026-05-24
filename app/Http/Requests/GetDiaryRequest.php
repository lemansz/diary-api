<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Override;

class GetDiaryRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'search'     => ['sometimes', 'nullable', 'string', 'max:100', 
            'regex:/^[\p{L}\p{N}\s\.,!\?\-\(\)\x{1F600}-\x{1F64F}\x{1F300}-\x{1F5FF}\x{1F680}-\x{1F6FF}\x{1F1E6}-\x{1F1FF}]+$/u'],

            'favourite'  => ['sometimes', 'nullable', 'boolean'],
            'start_date' => ['sometimes', 'nullable', 'date', 'date_format:Y-m-d', 'after_or_equal:start_date'],
            'end_date'   => ['sometimes', 'nullable', 'date', 'date_format:Y-m-d', 'after_or_equal:start_date'],
            'sort_by'    => ['sometimes', 'nullable', 'string', 'in:entry_date,title,mood,weather'],
            'sort_dir'   => ['sometimes', 'nullable', 'string', 'in:asc,desc,ASC,DESC'],   
        ];
    }

  
    public function messages()
    {
      return [ 
        'search.regex' => 'The search query contains unsupported special characters. Please use letters, numbers, basic punctuation, or emojis.',
        'search.max'   => 'The search query cannot be longer than 100 characters.',
       ];
    }
}
