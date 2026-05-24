<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDiaryRequest extends FormRequest
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
            'title'        => ['nullable', 'string', 'max:255'],
            'content'      => ['required', 'string', 'min:5', 'max:5000'],
            'entry_date'   => ['required', 'date', 'date_format:Y-m-d', 'before_or_equal:today', Rule::unique('diaries', 'entry_date')->where('user_id', $this->user()->id)],
            'mood'         => ['nullable', 'string', 'max:50'],
            'weather'      => ['nullable', 'string', 'max:50'],
            'is_favourite' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'content.required'           => 'Please write something in your diary entry.',
            'content.min'                => 'Your entry is too short. Write at least 5 characters.',
            'entry_date.before_or_equal' => 'You cannot write a diary entry for a future date.',
            'entry_date.unique'          => 'A diary of yours already has this entry date, please enter another date.',
        ];
    }

}
