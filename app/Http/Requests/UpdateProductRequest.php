<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Cho phép luôn, hoặc check quyền ở đây
    }

    public function rules(): array
    {
        return [
            'category_id'   => 'required|exists:categories,id',
            'name'          => 'required|string|max:255',
            'age'           => 'nullable|integer',
            'author'        => 'nullable|string|max:255',
            'publisher'     => 'nullable|string|max:255',
            'language'      => 'nullable|string|max:100',
            'price'         => 'required|numeric',
            'sale'          => 'nullable|numeric',
            'quantity'      => 'required|integer',
            'quantity_buy'  => 'nullable|integer',
            'weight'        => 'nullable|string|max:50',
            'size'          => 'nullable|string|max:50',
            'status'        => 'nullable|string|max:50',
            'is_active'     => 'boolean',
            'categ'         => 'nullable|string|max:50',
            'detail'        => 'nullable|string',
            'images'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'images_sup.*'  => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'category_id.required' => 'Vui lòng chọn danh mục.',
            'category_id.exists'   => 'Danh mục không tồn tại.',

            'name.required' => 'Tên sản phẩm là bắt buộc.',
            'name.max'      => 'Tên sản phẩm không được vượt quá 255 ký tự.',

            'age.integer'   => 'Độ tuổi phải là số nguyên.',
            'author.max'    => 'Tên tác giả không được vượt quá 255 ký tự.',
            'publisher.max' => 'Tên nhà xuất bản không được vượt quá 255 ký tự.',
            'language.max'  => 'Ngôn ngữ không được vượt quá 100 ký tự.',

            'price.required' => 'Giá sản phẩm là bắt buộc.',
            'price.numeric'  => 'Giá sản phẩm phải là số.',

            'sale.numeric'   => 'Giá khuyến mãi phải là số.',

            'quantity.required' => 'Số lượng là bắt buộc.',
            'quantity.integer'  => 'Số lượng phải là số nguyên.',

            'quantity_buy.integer' => 'Số lượng đã mua phải là số nguyên.',

            'weight.max' => 'Trọng lượng không được vượt quá 50 ký tự.',
            'size.max'   => 'Kích thước không được vượt quá 50 ký tự.',
            'status.max' => 'Trạng thái không được vượt quá 50 ký tự.',

            'categ.max'  => 'Loại sản phẩm không được vượt quá 50 ký tự.',

            'images.image'   => 'Ảnh chính phải là tệp hình ảnh.',
            'images.mimes'   => 'Ảnh chính phải có định dạng: jpg, jpeg, png, webp.',
            'images.max'     => 'Ảnh chính không được lớn hơn 2MB.',

            'images_sup.*.image' => 'Ảnh phụ phải là tệp hình ảnh.',
            'images_sup.*.mimes' => 'Ảnh phụ phải có định dạng: jpg, jpeg, png, webp.',
            'images_sup.*.max'   => 'Ảnh phụ không được lớn hơn 2MB.',
        ];
    }
}
