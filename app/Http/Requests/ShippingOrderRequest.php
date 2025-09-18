<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShippingOrderRequest extends FormRequest
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
            'name_recipient'   => 'required|string|max:100',
            'phone_recipient'  => 'required|string|max:20',
            'shipping_address' => 'required|string|max:255',
            // 'shipping_fee'     => 'required|numeric|min:0',
        ];
        
    }
      public function messages(): array
    {
        return [
            'name_recipient.required' => 'Bạn phải nhập tên người nhận.',
            'phone_recipient.required'=> 'Bạn phải nhập số điện thoại.',
            'shipping_address.required' => 'Bạn phải nhập địa chỉ giao hàng.',
        ];
    }
}
