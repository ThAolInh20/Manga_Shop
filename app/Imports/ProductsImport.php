<?php

namespace App\Imports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductsImport implements ToModel,WithHeadingRow
{
    public function model(array $row)
    {
        // Nếu tất cả các cột đều trống -> bỏ qua
    if (empty(array_filter($row))) {
        return null;
    }

    // Nếu thiếu cột name -> bỏ qua
    if (empty($row['name'])) {
        return null;
    }
       
        $product = new Product();
    $product->category_id = $row['category_id'] ?? 2;
    $product->name        = $row['name'] ?? 'NO_NAME';
    $product->age         = $row['age'] ?? null;
    $product->author      = $row['author'] ?? null;
    $product->publisher   = $row['publisher'] ?? null;
    $product->price       = $row['price'] ?? 0;
    $product->sale        = $row['sale'] ?? 0;
    $product->detail      = $row['detail'] ?? null;
    $product->categ       = $row['categ'] ?? null;
    $product->language    = $row['language'] ?? null;
    $product->weight      = $row['weight'] ?? null;
    $product->size        = $row['size'] ?? null;

    $product->save(); // lưu luôn

    // dd($product); // debug xem nó lưu ok chưa

    return $product;
    }
}
