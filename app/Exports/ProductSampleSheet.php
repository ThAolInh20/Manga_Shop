<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductSampleSheet implements FromArray, WithHeadings
{
    public function array(): array
    {
        return [
            [
                2, 'Sach_mau', 12, 'Tac_gia_A', 'NXB_Tre',
                50000, 10, 'Mo_ta_chi_tiet',
                'Sach_thieu_nhi', 'tieng_viet', '200g', '20x14cm'
            ],
        ];
    }

    public function headings(): array
    {
        return [
            'category_id',
            'name',
            'age',
            'author',
            'publisher',
            'price',
            'sale',
            'detail',
            'categ',
            'language',
            'weight',
            'size',
        ];
    }
}
