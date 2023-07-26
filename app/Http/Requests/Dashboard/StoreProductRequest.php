<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
            'slug' => 'nullable|alpha_dash|unique:products,slug,'.$this->request->get('id', 0),
            'categories' => 'required|array|min:1',
            'categories.*' => 'required|int|distinct|min:1',
            'images' => 'nullable|array',
            'images.*' => 'image|max:1000|mimes:jpg,jpeg,png',
            'price' => 'required|numeric|min:0|not_in:01',
        ];

        foreach (config('settings.languages') as $language => $locale) {
            $rules['name__'.$locale] = [
                'required',
                'max:'.config('settings.schema.string_length'),
            ];
            $rules['description__'.$locale] = 'nullable';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'categories.required' => __('validation.category-required'),
            'image.*.mimes' => 'Allow to .jpeg, .png, .jpg',
            'image.*.max' => 'An image is max 1Mb',
        ];
    }
}
