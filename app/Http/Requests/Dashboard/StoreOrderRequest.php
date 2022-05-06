<?php

namespace App\Http\Requests\Dashboard;

use App\Models\Order;
use App\Rules\CreditCard;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Request;


class StoreOrderRequest extends FormRequest
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
            'customer_name'                         => 'required|min:2|max:'.config('settings.schema.string_length'),
            'customer_email'                        => 'required|email',
            'billing_address'                       => 'required|min:8|max:'.config('settings.schema.string_length'),
            'shipping_address_as_billing_address'   => 'nullable'
        ];

        if (!$this->request->get('shipping_address_as_billing_address')) {
            $rules['shipping_address']  = 'required|min:8|max:'.config('settings.schema.string_length');
        }

        if (Request::is('*dashboard*')) {
            $rules['status'] = Rule::in(Order::STATUSES);
            $rules['user_id']  = 'required|exists:users,id';
        }

        if (Request::is('*checkout*')) {
            $rules['credit_card_holder']            = 'required|min:4|max:'.config('settings.schema.string_length');
            $rules['credit_card_number']            = ['required', new CreditCard];
            $rules['credit_card_expiration_month']  = 'required|digits:2';
            $rules['credit_card_expiration_year']   = 'required|digits:4';
            $rules['credit_card_cvv']               = 'required|digits:3';
        }

        return $rules;
    }
}
