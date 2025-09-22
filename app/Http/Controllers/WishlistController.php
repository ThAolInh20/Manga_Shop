<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use App\Models\Account;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class WishlistController extends Controller
{
    /**
     * Danh sách wishlist của user (hoặc guest)
     */
    public function index()
    {
        if (Auth::check()) {
        $wishlists = Auth::user()->wishlist()->with('product')->paginate(10);
      

        // map để trả về cấu trúc giống guest
        $wishlists->getCollection()->transform(function ($item) {
            return $item->product; // trả về Product
        });

    } else {
        $wishlistIds = session()->get('wishlist', []);
        $wishlists = Product::whereIn('id', $wishlistIds)->paginate(10);
    }

    return response()->json($wishlists);
    }
    public function showWishlist(){
        return view('user.wishlist.list');
    }


    /**
     * Gợi ý sản phẩm dựa theo wishlist hoặc top bán chạy
     */
    public function suggestProducts()
    {
        $user = Auth::user();
        $suggested = collect();

        // if ($user) {
        //     $likedProducts = $user->wishlist()->with('product')->get()->pluck('product');

        //     if ($likedProducts->isNotEmpty()) {
        //         $authors    = $likedProducts->pluck('author')->filter()->unique();
        //         $categories = $likedProducts->pluck('categ')->filter()->unique();
        //         $ages       = $likedProducts->pluck('age')->filter()->unique();

        //         $suggested = Product::query()
        //             ->whereNotIn('id', $likedProducts->pluck('id'))
        //             ->where(function ($q) use ($authors, $categories, $ages) {
        //                 if ($authors->isNotEmpty()) $q->orWhereIn('author', $authors);
        //                 if ($categories->isNotEmpty()) $q->orWhereIn('categ', $categories);
        //                 if ($ages->isNotEmpty()) $q->orWhereIn('age', $ages);
        //             })
        //             ->take(20)
        //             ->get();
        //     }
        // }

       
        $suggested = Product::orderBy('quantity_buy', 'desc')->take(12)->get();
        

        // Thêm flag in_wishlist
        $wishlistIds = $user
            ? $user->wishlist()->pluck('product_id')->toArray()
            : session()->get('wishlist', []);

        $suggested->transform(function ($product) use ($wishlistIds) {
            $product->in_wishlist = in_array($product->id, $wishlistIds);
            return $product;
        });

        return response()->json($suggested);
    }

    /**
     * Thêm sản phẩm vào wishlist
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id'            
        ]);

        $productId = $request->product_id;
        // $userId = $request->user_id;
        // $user = Auth::user();
        // Log::info('User info: ', ['user' => $user]);
        // $user = Account::where('id',3)->first();
        //     Wishlist::create([
        //         'account_id'    => $user->id,
        //         'product_id' => $productId,
        //     ]);
        if (Auth::check()) {
            $user = Auth::user();

            $exists = Wishlist::where('account_id', $user->id)
                ->where('product_id', $productId)
                ->first();

            if ($exists) {
                return response()->json(['message' => 'Sản phẩm đã có trong wishlist'],200);
            }

            Wishlist::create([
                'account_id'    => $user->id,
                'product_id' => $productId,
            ]);
        } else {
            $wishlist = session()->get('wishlist', []);
            if (!in_array($productId, $wishlist)) {
                $wishlist[] = $productId;
                session()->put('wishlist', $wishlist);
            }
        }

        return response()->json(['message' => 'Đã thêm vào wishlist'],201);
    }

    /**
     * Xóa sản phẩm khỏi wishlist
     */
    public function destroy($id)
    {
        if (Auth::check()) {
            $wishlist = Wishlist::where('account_id', Auth::id())
                ->where('product_id', $id)
                ->first();

            if ($wishlist) {
                $wishlist->delete();
            }
        } else {
            $wishlist = session()->get('wishlist', []);
            $wishlist = array_diff($wishlist, [$id]);
            session()->put('wishlist', $wishlist);
        }

        return response()->json(['message' => 'Đã xoá khỏi wishlist'],201 );
    }
}
