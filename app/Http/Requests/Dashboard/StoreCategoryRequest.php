<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;


class StoreCategoryRequest extends FormRequest
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
     */
    public function rules(): array
    {
        $rules = [
            'slug' => 'nullable|alpha_dash|unique:categories,slug,'.$this->request->get('id', 0)
        ];

        foreach (config('settings.languages') as $language => $locale) {
            $rules['name__'.$locale] = [
                'required',
                'max:'.config('settings.schema.string_length')
            ];
        }

        if ((int)$this->request->get('parent_id', 0)) {
            $rules['parent_id'] = [
                'required',
                'exists:categories,id'
            ];
        }

        return $rules;
    }

    public function messages(): array
    {
        $messages = [];
        foreach (config('settings.languages') as $language => $locale) {
            $messages['name__'.$locale.'.required'] = __('validation.required');
            $messages['name__'.$locale.'.max'] = 'A name is max '.config('settings.schema.string_length').' length';
        }

        return $messages;
    }
}
