<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ProductsSampleExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            'Mẫu nhập'   => new ProductSampleSheet(),
            'Hướng dẫn'  => new ProductGuideSheet(),
        ];
    }
}
