<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->latest()->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'category_id' => 'required|integer',
            'price'       => 'required|numeric',
            'images'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'images_sup.*'=> 'nullable|image|mimes:jpg,jpeg,png|max:2048',
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

        return redirect()->route('products.index')->with('success', 'Thêm sản phẩm thành công');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'category_id' => 'required|integer',
            'price'       => 'required|numeric',
            'images'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'images_sup.*'=> 'nullable|image|mimes:jpg,jpeg,png|max:2048',
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
