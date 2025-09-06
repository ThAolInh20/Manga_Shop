<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AccountRequest extends FormRequest
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
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:accounts,email',
            'password' => 'required|string|min:6',
            'role'     => 'required|integer',
            'image'    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            
        ];
    }
    public function messages(): array
    {
        return [
            'name.required' => 'Vui lòng nhập tên.',
            'name.max'      => 'Tên không được vượt quá 100 ký tự.',

            'email.required' => 'Vui lòng nhập email.',
            'email.email'    => 'Email không hợp lệ.',
            'email.unique'   => 'Email đã tồn tại.',

            'password.required' => 'Vui lòng nhập mật khẩu.',
            'password.min'      => 'Mật khẩu phải có ít nhất 6 ký tự.',

            'role.required' => 'Vui lòng chọn vai trò.',
            'role.integer'  => 'Vai trò không hợp lệ.',

            'image.image'  => 'File phải là ảnh.',
            'image.mimes'  => 'Ảnh phải có định dạng: jpg, jpeg, png, webp.',
            'image.max'    => 'Ảnh không được vượt quá 2MB.',
        ];
    }
}
