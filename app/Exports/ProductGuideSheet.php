<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;

class ProductGuideSheet implements FromArray
{
    public function array(): array
    {
        return [
            ['Cột', 'Ý nghĩa'],
            ['category_id', 'ID danh mục (lấy từ bảng categories)'],
            ['name', 'Tên sản phẩm'],
            ['age', 'Độ tuổi phù hợp'],
            ['author', 'Tên tác giả'],
            ['publisher', 'Nhà xuất bản'],
            ['price', 'Giá gốc (VNĐ)'],
            ['sale', 'Phần trăm giảm giá (%)'],
            ['detail', 'Mô tả chi tiết'],
            ['categ', 'Loại sách (vd: thiếu nhi, tiểu thuyết...)'],
            ['is_active', 'Trạng thái (1=Hiện, 0=Ẩn)'],
            ['language', 'Ngôn ngữ'],
            ['weight', 'Trọng lượng (vd: 200g)'],
            ['size', 'Kích thước (vd: 20x14cm)'],
        ];
    }
}
