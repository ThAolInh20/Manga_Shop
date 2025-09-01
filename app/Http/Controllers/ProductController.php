<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        // Search theo tên hoặc tác giả
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                ->orWhere('author', 'like', "%$search%");
            });
        }

        // Lọc theo khoảng giá
        if ($request->filled('price_range')) {
            [$min, $max] = explode('-', $request->price_range);
            $query->whereBetween('price', [(int)$min, (int)$max]);
        }

        // Lọc theo khoảng số lượng
        if ($request->filled('quantity_range')) {
            [$min, $max] = explode('-', $request->quantity_range);
            $query->whereBetween('quantity', [(int)$min, (int)$max]);
        }

        // Sắp xếp
        $sort = $request->get('sort', 'id');
        $order = $request->get('order', 'asc');
        $query->orderBy($sort, $order);

        // Phân trang
        $perPage = $request->get('per_page', 10);
        $products = $query->with('category')->paginate($perPage)->appends($request->all());

        // Nếu là AJAX trả về table
        if ($request->ajax()) {
            return view('admin.products.index', compact('products'))->render();
        }

        // Lần đầu load view
        return view('admin.products.index', compact('products'));
    }



    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name'        => 'required|string|max:255',
                'category_id' => 'required|integer|exists:categories,id',
                'price'       => 'required|numeric|min:0',
                'images'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
                'images_sup.*'=> 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            ]);

            // Upload ảnh chính
            if ($request->hasFile('images')) {
                $validated['images'] = $request->file('images')->store('products', 'public');
            }

            // Upload nhiều ảnh phụ
            if ($request->hasFile('images_sup')) {
                $supImages = [];
                foreach ($request->file('images_sup') as $file) {
                    $supImages[] = $file->store('products', 'public');
                }
                $validated['images_sup'] = json_encode($supImages);
            }

            Product::create($validated);

            return redirect()->route('products.index')
                ->with('success', 'Thêm sản phẩm thành công');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Nếu validate lỗi, Laravel sẽ tự redirect kèm errors nên không cần xử lý thêm
            throw $e;
        } catch (\Exception $e) {
            // Nếu có lỗi khác (DB, upload, ...)
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Có lỗi xảy ra: ' . $e->getMessage()]);
        }
    }


    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'category_id'   => 'required|exists:categories,id',
            'name'          => 'required|string|max:255',
            'age'           => 'nullable|integer',
            'author'        => 'nullable|string|max:255',
            'publisher'     => 'nullable|string|max:255',
            'language'      => 'nullable|string|max:100',
            'price'         => 'required|numeric',
            'sale'          => 'nullable|numeric',
            'quantity'      => 'required|integer',
            'quantity_buy'  => 'nullable|integer',
            'weight'        => 'nullable|string|max:50',
            'size'          => 'nullable|string|max:50',
            'status'        => 'nullable|string|max:50',
            'is_active'     => 'boolean',
            'categ'         => 'nullable|string|max:50',
            'detail'        => 'nullable|string',
            'images'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'images_sup.*'=> 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('images')) {
            if ($product->images) Storage::disk('public')->delete($product->images);
            $validated['images'] = $request->file('images')->store('products', 'public');
        }

        if ($request->hasFile('images_sup')) {
            if ($product->images_sup) {
                foreach (json_decode($product->images_sup) as $oldFile) {
                    Storage::disk('public')->delete($oldFile);
                }
            }
            $supImages = [];
            foreach ($request->file('images_sup') as $file) {
                $supImages[] = $file->store('products', 'public');
            }
            $validated['images_sup'] = json_encode($supImages);
        }

        $product->update($validated);

        return redirect()->route('products.index')->with('success', 'Cập nhật sản phẩm thành công');
    }

    public function destroy(Product $product)
    {
        if ($product->images) Storage::disk('public')->delete($product->images);
        if ($product->images_sup) {
            foreach (json_decode($product->images_sup) as $oldFile) {
                Storage::disk('public')->delete($oldFile);
            }
        }

        $product->delete();

        return redirect()->route('products.index')->with('success', 'Xoá sản phẩm thành công');
    }
}
