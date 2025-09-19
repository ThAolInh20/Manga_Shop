<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $categories = Category::withCount('products')->get();
        $categories = Category::with(['updatedBy', 'createdBy'])
    ->withCount('products')
    ->get();
        return view('admin.categories.index', compact('categories'));
    }
    public function listCategories(){
        $categories = Category::has('products')->get();

        // Trả về JSON
        return response()->json($categories);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         $validated = $request->validate([
            'name'   => 'required|string|max:255|unique:categories',
            'detail' => 'nullable|string',
        ]);

        Category::create($validated);

        return redirect()->route('categories.index')->with('success', 'Thêm danh mục thành công');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
         $products = $category->products()->paginate(10); // 10 sp / trang
    
        return view('admin.categories.show', compact('category', 'products'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        $category->load('products');
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name'   => 'required|string|max:255|unique:categories,name,' . $category->id,
            'detail' => 'nullable|string',
        ]);

        $category->update($validated);

        return redirect()->route('categories.index')->with('success', 'Cập nhật danh mục thành công');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        if($category->id==2){
            return redirect()->route('categories.index')->with('error', 'Không thể xóa danh mục này');
        }
        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Xóa danh mục thành công');
    }
}
