<?php

namespace App\Http\Controllers;

use App\Models\ProductOrder;
use Illuminate\Http\Request;

class ProductOrderController extends Controller
{
    public function add($product, $order_id)
    {
         ProductOrder::create([
        'product_id' => $product['product_id'], // $product là mảng
        'order_id' => $order_id,
        'quantity' => $product['quantity'],
        'price' => $product['price']
    ]);
        // return response()->json(['message' => 'Product orders added successfully'], 201);
    }
    
}
