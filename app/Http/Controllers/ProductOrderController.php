<?php

namespace App\Http\Controllers;

use App\Models\ProductOrder;
use Illuminate\Http\Request;

class ProductOrderController extends Controller
{
    public function add($jsonData)
    {
        $data = json_decode($jsonData, true);
        foreach ($data as $item) {
            ProductOrder::create([
                'product_id' => $item['product_id'],
                'order_id' => $item['order_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price']
            ]);
        }
        return response()->json(['message' => 'Product orders added successfully'], 201);
    }
}
