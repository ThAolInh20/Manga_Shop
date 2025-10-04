<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index(){
        return view('user.cart.list');
    }
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
        $product = Product::find($productId);
        if ($product->quantity <= 0) {
            return response()->json(['message' => 'Sản phẩm đã hết hàng']);
        }
        
        if ($exists) {
            if($exists->quantity + 1 > $product->quantity){
                return response()->json(['message' => 'Sản phẩm đã quá số lượng']);
            }
            $exists->quantity += 1;
            // tăng số lượng
            $exists->save();

            return response()->json([
                'message' => 'Đã tăng số lượng sản phẩm trong giỏ hàng',
                'cart' => $exists
            ], 200);
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
        'quantity' => 'required|integer|min:0'
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
    public function removeV2($product,$userId)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

       

        $cartItem = Cart::where('account_id', $userId)
                        ->where('product_id', $product['product_id'])
                        ->first();

        if($cartItem){
            $cartItem->delete();
            return true;
        }
        return false;
        

    }
   

    // // Lấy danh sách giỏ hàng của user
    public function list()
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $cartItems = Cart::with('product')->where('account_id', Auth::id())->orderBy('created_at')->get();

        return response()->json($cartItems);
    }
}
