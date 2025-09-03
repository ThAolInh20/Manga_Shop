<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
// use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::check()) {
            $wishlists = Auth::user()->wishlist()->with('product')->paginate(10);
        } else {
            // Guest: lấy wishlist từ session
            $wishlistIds = session()->get('wishlist', []);
            $wishlists = Product::whereIn('id', $wishlistIds)->paginate(10);
        }

        return view('wishlist.index', compact('wishlists'));
    }
    public function suggestProducts()
    {
        $user = Auth::user();
        $suggested = collect();

        if ($user) {
            // Lấy danh sách sản phẩm mà user đã thích (wishlist)
            $likedProducts = $user->wishlist()->with('product')->get()->pluck('product');

            if ($likedProducts->isNotEmpty()) {
                $authors    = $likedProducts->pluck('author')->filter()->unique();
                $categories = $likedProducts->pluck('categ')->filter()->unique();
                $ages       = $likedProducts->pluck('age')->filter()->unique();

                // Query gợi ý
                $suggested = Product::query()
                    ->whereNotIn('id', $likedProducts->pluck('id')) // loại bỏ sách đã thích
                    ->where(function ($q) use ($authors, $categories, $ages) {
                        if ($authors->isNotEmpty()) {
                            $q->orWhereIn('author', $authors);
                        }
                        if ($categories->isNotEmpty()) {
                            $q->orWhereIn('categ', $categories);
                        }
                        if ($ages->isNotEmpty()) {
                            $q->orWhereIn('age', $ages);
                        }
                    })
                    ->take(20)
                    ->get();
            }
        }

        // Fallback: nếu chưa đăng nhập hoặc chưa có gợi ý → bán chạy nhất
        if ($suggested->isEmpty()) {
            $suggested = Product::orderBy('quantity_buy', 'desc')
                ->take(20)
                ->get();
        }

        // 🚀 Trả về JSON cho Vue
        return response()->json($suggested);
    }

    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $productId = $request->product_id;

        if (Auth::check()) {
            // User đã login
            $user = Auth::user();
            $exists = Wishlist::where('user_id', $user->id)
                ->where('product_id', $productId)
                ->first();

            if ($exists) {
                return response()->json(['message' => 'Sản phẩm đã có trong wishlist']);
            }

            Wishlist::create([
                'user_id'    => $user->id,
                'product_id' => $productId,
            ]);
        } else {
            // Guest: lưu trong session
            $wishlist = session()->get('wishlist', []);
            if (!in_array($productId, $wishlist)) {
                $wishlist[] = $productId;
                session()->put('wishlist', $wishlist);
            }
        }

        return response()->json(['message' => 'Đã thêm vào wishlist']);
    }

   

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Wishlist $wishlist, $id)
    {
        if (Auth::check()) {
            $wishlist = Wishlist::where('user_id', Auth::id())
                ->where('product_id', $id)
                ->first();

            if ($wishlist) {
                $wishlist->delete();
            }
        } else {
            // Guest: xoá khỏi session
            $wishlist = session()->get('wishlist', []);
            $wishlist = array_diff($wishlist, [$id]);
            session()->put('wishlist', $wishlist);
        }

        return response()->json(['message' => 'Đã xoá khỏi wishlist']);
    }
}
