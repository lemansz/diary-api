<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDiaryRequest extends FormRequest
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
       
        $diaryRoute = $this->route('diaries');
        $diaryId = is_object($diaryRoute) ? $diaryRoute->id : $diaryRoute;

        return [
            'title'        => ['nullable', 'string', 'max:255'],
            'content'      => ['nullable', 'string', 'min:5', 'max:5000'],
            'mood'         => ['nullable', 'string', 'max:50'],
            'weather'      => ['nullable', 'string', 'max:50'],
            'is_favourite' => ['nullable', 'boolean'],
            'entry_date'   => ['nullable', 'date', 'date_format:Y-m-d', 'before_or_equal:today', Rule::unique('diaries', 'entry_date')->where('user_id', $this->user()->id)->ignore($diaryId)],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'entry_date.unique' => 'A diary of yours already has this entry date, please enter another date.',
        ];
    }
}
