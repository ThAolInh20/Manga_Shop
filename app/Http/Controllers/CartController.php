<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    // Thêm sản phẩm vào giỏ hàng
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $productId = $request->product_id;
        $price = $request->price - ($request->price * ($request->sale ?? 0) / 100);
        $userId = Auth::id();

        // Kiểm tra nếu đã có trong giỏ thì bỏ qua
        $exists = Cart::where('account_id', $userId)
                      ->where('product_id', $productId)
                      ->first();

        if ($exists) {
            return response()->json(['message' => 'Sản phẩm đã có trong giỏ hàng'], 200);
        }

        Cart::create([
            'account_id' => $userId,
            'product_id' => $productId,
            'quantity'=> 1,
            'price'=>$price
        ]);

        return response()->json(['message' => 'Đã thêm sản phẩm vào giỏ hàng'], 201);
    }
public function update(Request $request, $productId)
{
    $request->validate([
        'quantity' => 'required|integer|min:1'
    ]);

    if (!Auth::check()) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    $userId = Auth::id();

    $cart = Cart::where('account_id', $userId)
                ->where('product_id', $productId)
                ->first();

    if (!$cart) {
        return response()->json(['error' => 'Sản phẩm không tồn tại trong giỏ hàng'], 404);
    }

    $cart->quantity = $request->quantity;
    $cart->save();

    return response()->json([
        'message' => 'Cập nhật số lượng thành công',
        'cart' => $cart
    ]);
}

    // Xóa sản phẩm khỏi giỏ hàng
    public function remove($productId)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $userId = Auth::id();

        $cartItem = Cart::where('account_id', $userId)
                        ->where('product_id', $productId)
                        ->first();

        if (!$cartItem) {
            return response()->json(['message' => 'Sản phẩm không tồn tại trong giỏ hàng'], 404);
        }

        $cartItem->delete();

        return response()->json(['message' => 'Đã xóa sản phẩm khỏi giỏ hàng']);
    }

    // Lấy danh sách giỏ hàng của user
    public function index()
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $cartItems = Cart::with('product')->where('account_id', Auth::id())->get();

        return response()->json($cartItems);
    }
}
