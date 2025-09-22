<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductsExport implements FromCollection, WithHeadings
{
    protected $categoryId;

    public function __construct($categoryId = null)
    {
        $this->categoryId = $categoryId;
    }

    public function collection()
    {
        $query = Product::with('category:id,name');

        if ($this->categoryId) {
            $query->where('category_id', $this->categoryId);
        }

        return $query->get([
            'id',
            'category_id',
            'name',
            'age',
            'author',
            'publisher',
            'quantity',
            'price',
            'sale',
            'price_sale',
            'detail',
            'categ',
            
            'is_active',
            'language',
            'weight',
            'size',
            'quantity_buy',
            'created_at',
            'updated_at',
        ]);
    }

    public function headings(): array
    {
        return [
            'ID',
            'Danh mục',
            'Tên',
            'Tuổi',
            'Tác giả',
            'Nhà xuất bản',
            'Số lượng',
            'Giá gốc',
            'Sale (%)',
            'Giá sau sale',
            'Chi tiết',
            'Categ',
            'Trạng thái',
            
            'Ngôn ngữ',
            'Trọng lượng',
            'Kích thước',
            'Số lượng đã bán',
            'Ngày tạo',
            'Ngày cập nhật',
        ];
    }
}

