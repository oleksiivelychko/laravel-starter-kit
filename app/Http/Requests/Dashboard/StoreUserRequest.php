<?php

namespace App\Http\Requests\Dashboard;

use App\Models\Permission;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreUserRequest extends FormRequest
{
    public const PERMISSION_ACl = 'manage-acl';

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $permission = Permission::whereSlug(self::PERMISSION_ACl)->first();
        if ($permission && Auth::user()->hasPermission($permission)) {
            return true;
        }

        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules = [
            'name' => 'required|min:2|max:'.config('settings.schema.string_length'),
            'email' => 'required|email',
            'avatar' => 'image|max:1000|mimes:jpg,jpeg,png',
        ];

        $passwordRules = [
            'string',
            'min:6',              // must be at least 6 characters in length
            // 'regex:/[a-z]/',      // must contain at least one lowercase letter
            // 'regex:/[A-Z]/',      // must contain at least one uppercase letter
            // 'regex:/[0-9]/',      // must contain at least one digit
            // 'regex:/[@$!%*#?&]/', // must contain a special character
        ];

        if (!$this->request->get('id')) {
            $rules['email'] = 'required|email|unique:users,email';
            $rules['password'] = array_merge(['required'], $passwordRules);
        }

        if ($this->request->get('password')) {
            $rules['password'] = $passwordRules;
        }

        return $rules;
    }
}
