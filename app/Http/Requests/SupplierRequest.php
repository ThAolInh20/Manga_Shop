<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SupplierRequest extends FormRequest
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
            'name'          => 'required|string|max:255',
            'address'       => 'nullable|string',
            'phone'         => 'required|string|max:20',
            'email'         => 'required|email',
            'tax_code'      => 'required|string|max:50',
            'contract'      => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'link_contract' => 'nullable|url',
        ];
    }
     public function messages(): array
    {
        return [
            'name.required'       => 'Vui lòng nhập tên nhà cung cấp.',
            'name.string'         => 'Tên nhà cung cấp phải là chuỗi ký tự.',
            'name.max'            => 'Tên nhà cung cấp không được vượt quá 255 ký tự.',

            'address.string'      => 'Địa chỉ phải là chuỗi ký tự.',

            'phone.required'      => 'Vui lòng nhập số điện thoại.',
            'phone.string'        => 'Số điện thoại phải là chuỗi ký tự.',
            'phone.max'           => 'Số điện thoại không được vượt quá 20 ký tự.',

            'email.required'      => 'Vui lòng nhập email.',
            'email.email'         => 'Email không hợp lệ.',

            'tax_code.required'   => 'Vui lòng nhập mã số thuế.',
            'tax_code.string'     => 'Mã số thuế phải là chuỗi ký tự.',
            'tax_code.max'        => 'Mã số thuế không được vượt quá 50 ký tự.',

            'contract.file'       => 'Hợp đồng phải là file hợp lệ.',
            'contract.mimes'      => 'Hợp đồng chỉ chấp nhận định dạng: jpg, jpeg, png, pdf.',
            'contract.max'        => 'File hợp đồng không được vượt quá 2MB.',

            'link_contract.url'   => 'Link hợp đồng phải là một URL hợp lệ.',
        ];
    }
}
