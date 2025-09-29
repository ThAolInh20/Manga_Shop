<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Support\Facades\Auth;

use App\Models\ProductSupplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use App\Exports\ProductsExport;
use App\Imports\ProductsImport;
use App\Exports\ProductsSampleExport;

use Maatwebsite\Excel\Facades\Excel;

// use App\Models\Category;


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
        if ($request->filled('quantity_max')) {
            $query->where('quantity', '<=', $request->quantity_max);
        }
        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
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
        $categories =Category::all();
        // Nếu là AJAX trả về table
        if ($request->ajax()) {
            return view('admin.products.index', compact('products','categories'))->render();
        }
        

        // Lần đầu load view
        return view('admin.products.index', compact('products','categories'));
    }
            public function import($productId)
        {
            $product = Product::findOrFail($productId);
            $suppliers = Supplier::all();
            return view('admin.products.importProduct', compact('product','suppliers'));
        }
       public function importStore(Request $request, $productId)
{
    $request->validate([
        'quantity' => 'required|integer|min:1',
        'import_price' => 'required|numeric|min:0',
        'supplier_id' => 'required|exists:suppliers,id',
        'detail' => 'nullable|string|max:500',
    ]);

    $product = Product::findOrFail($productId);

    // Lưu lịch sử nhập kho
    ProductSupplier::create([
        'product_id'   => $product->id,
        'supplier_id'  => $request->supplier_id,
        'quantity'     => $request->quantity,
        'import_price' => $request->import_price,
        'import_by'    => Auth::user()->id,
        'detail'       => $request->detail,
    ]);
    // Cập nhật số lượng sản phẩm

    return redirect()
        ->route('products.edit',$product->id)
        ->with('success', 'Nhập kho sản phẩm thành công!');
}
        



    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
{
    try {
        // Validate dữ liệu
        $validated = $request->validate([
            'category_id'   => 'required|exists:categories,id',
            'name'          => 'required|string|max:255',
            'age'           => 'nullable|integer',
            'author'        => 'nullable|string|max:255',
            'publisher'     => 'nullable|string|max:255',
            'language'      => 'nullable|string|max:100',
            'price'         => 'required|numeric',
            'sale'          => 'nullable|numeric',
            'weight'        => 'nullable|string|max:50',
            'size'          => 'nullable|string|max:50',
            'categ'         => 'nullable|string|max:50',
            'detail'        => 'nullable|string',
            'images'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'images_sup.*'  => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'category_id.required' => 'Vui lòng chọn danh mục.',
            'category_id.exists'   => 'Danh mục không tồn tại.',
            'name.required'        => 'Tên sản phẩm là bắt buộc.',
            'name.max'             => 'Tên sản phẩm không được vượt quá 255 ký tự.',
            // 'author.required'      => 'Tên tác giả là bắt buộc.',
            'author.max'           => 'Tên tác giả không được vượt quá 255 ký tự.',
            'publisher.max'        => 'Tên nhà xuất bản không được vượt quá 255 ký tự.',
            'language.max'         => 'Ngôn ngữ không được vượt quá 100 ký tự.',
            'price.required'       => 'Giá sản phẩm là bắt buộc.',
            'price.numeric'        => 'Giá sản phẩm phải là số.',
            'sale.numeric'         => 'Giá khuyến mãi phải là số.',
            'weight.max'           => 'Trọng lượng không được vượt quá 50 ký tự.',
            'size.max'             => 'Kích thước không được vượt quá 50 ký tự.',
            'categ.max'            => 'Loại sản phẩm không được vượt quá 50 ký tự.',
            'images.image'         => 'Ảnh chính phải là tệp hình ảnh.',
            'images.mimes'         => 'Ảnh chính phải có định dạng: jpg, jpeg, png, webp.',
            'images.max'           => 'Ảnh chính không được lớn hơn 2MB.',
            'images_sup.*.image'   => 'Ảnh phụ phải là tệp hình ảnh.',
            'images_sup.*.mimes'   => 'Ảnh phụ phải có định dạng: jpg, jpeg, png, webp.',
            'images_sup.*.max'     => 'Ảnh phụ không được lớn hơn 2MB.',
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
        // Laravel tự redirect và kèm errors nếu validate lỗi
        throw $e;
    } catch (\Exception $e) {
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
        // 'quantity'      => 'required|integer',
        // 'quantity_buy'  => 'nullable|integer',
        'weight'        => 'nullable|string|max:50',
        'size'          => 'nullable|string|max:50',
        'status'        => 'nullable|string|max:50',
        'is_active'     => 'boolean',
        'categ'         => 'nullable|string|max:50',
        'detail'        => 'nullable|string',
        'images'        => 'nullable|image|mimes:jpg,jpeg,png,webp',
        'images_sup.*'  => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
    ], [
        'category_id.required' => 'Vui lòng chọn danh mục.',
        'category_id.exists'   => 'Danh mục không tồn tại.',

        'name.required' => 'Tên sản phẩm là bắt buộc.',
        'name.max'      => 'Tên sản phẩm không được vượt quá 255 ký tự.',

        'age.integer'   => 'Độ tuổi phải là số nguyên.',
        'author.max'    => 'Tên tác giả không được vượt quá 255 ký tự.',
        'publisher.max' => 'Tên nhà xuất bản không được vượt quá 255 ký tự.',
        'language.max'  => 'Ngôn ngữ không được vượt quá 100 ký tự.',

        'price.required' => 'Giá sản phẩm là bắt buộc.',
        'price.numeric'  => 'Giá sản phẩm phải là số.',

        'sale.numeric'   => 'Giá khuyến mãi phải là số.',

        'quantity.required' => 'Số lượng là bắt buộc.',
        'quantity.integer'  => 'Số lượng phải là số nguyên.',

        'quantity_buy.integer' => 'Số lượng đã mua phải là số nguyên.',

        'weight.max' => 'Trọng lượng không được vượt quá 50 ký tự.',
        'size.max'   => 'Kích thước không được vượt quá 50 ký tự.',
        'status.max' => 'Trạng thái không được vượt quá 50 ký tự.',

        'categ.max'  => 'Loại sản phẩm không được vượt quá 50 ký tự.',

        'images.image'   => 'Ảnh chính phải là tệp hình ảnh.',
        'images.mimes'   => 'Ảnh chính phải có định dạng: jpg, jpeg, png, webp.',
        'images.max'     => 'Ảnh chính không được lớn hơn 2MB.',

        'images_sup.*.image' => 'Ảnh phụ phải là tệp hình ảnh.',
        'images_sup.*.mimes' => 'Ảnh phụ phải có định dạng: jpg, jpeg, png, webp.',
        'images_sup.*.max'   => 'Ảnh phụ không được lớn hơn 2MB.',
    ]);


    // Xử lý ảnh chính
    if ($request->has('remove_image_main')) {
        if ($product->images && Storage::disk('public')->exists($product->images)) {
            Storage::disk('public')->delete($product->images);
        }
        $validated['images'] = null; // set DB = null
    }
    if ($request->hasFile('images')) {
        if ($product->images) {
            Storage::disk('public')->delete($product->images);
        }
        $validated['images'] = $request->file('images')->store('products', 'public');
    }
    

    // Lấy danh sách ảnh phụ hiện tại
    $supImages = $product->images_sup ? json_decode($product->images_sup, true) : [];

    // Nếu có request xoá ảnh phụ
    if ($request->has('remove_images_sup')) {
        foreach ($request->remove_images_sup as $img) {
            if (($key = array_search($img, $supImages)) !== false) {
                unset($supImages[$key]);
                if (Storage::disk('public')->exists($img)) {
                    Storage::disk('public')->delete($img);
                }
            }
        }
    }

    // Nếu có upload thêm ảnh phụ mới
    if ($request->hasFile('images_sup')) {
        foreach ($request->file('images_sup') as $file) {
            $supImages[] = $file->store('products', 'public');
        }
    }

    // Lưu lại danh sách ảnh phụ
    $validated['images_sup'] = json_encode(array_values($supImages));

    // Update sản phẩm
    $product->update($validated);

    return redirect()->back()->with('success', 'Cập nhật sản phẩm thành công');

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
    public function showProductForUser(Product $product)
    {
        return view('user.products.show', compact('product'));
    }
    public function filterField(Request $request)
    {
        $field = $request->query('field');

        // Kiểm tra field có tồn tại trong products
        if (!Schema::hasColumn('products', $field)) {
            return response()->json([], 400);
        }

        // Lấy giá trị duy nhất dùng Eloquent
        $values = Product::query()
         ->whereNotNull($field)
            ->distinct()
            ->pluck($field);

      
        return response()->json($values);
    }
   public function getAllProducts(Request $request)
{
    $user = auth()->user();
    $query = Product::query() ->where('is_active', 1);;

    // 🔍 Tìm kiếm theo tên sản phẩm hoặc tác giả
    if ($request->search) {
        $search = strtolower($request->search);

        $query->where(function ($q) use ($search) {
            $q->whereRaw('LOWER(name) LIKE ?', ["%{$search}%"])
            ->orWhereRaw('LOWER(author) LIKE ?', ["%{$search}%"])
            // ->orWhereRaw('LOWER(categ) LIKE ?', ["%{$search}%"])
            // ->orWhereRaw('LOWER(publisher) LIKE ?', ["%{$search}%"])
            ;
        });
    }
    else if($request->category_id){
        $query=$query->where('category_id',$request->category_id);
    }
    

    // 💰 Lọc giá
    if ($request->minPrice) {
        $query->where('price', '>=', $request->minPrice);
    }
    if ($request->maxPrice) {
        $query->where('price', '<=', $request->maxPrice);
    }

    // 🏷 Lọc theo category / author / publisher nếu có
    if ($request->categ) {
        $categs = explode(',', $request->categ);
        $query->whereIn('categ', $categs);
    }
    if($request->category_id){
        $category_id = explode(',', $request->category_id);
        $query->whereIn('category_id', $category_id);
    }
    if ($request->author) {
        $authors = explode(',', $request->author);
        $query->whereIn('author', $authors);
    }
    if ($request->publisher) {
        $publishers = explode(',', $request->publisher);
        $query->whereIn('publisher', $publishers);
    }

    // 🔽 Sắp xếp
    $sortBy = $request->sortBy ?? 'name';
    $sortOrder = $request->sortOrder ?? 'asc';
    $query->orderBy($sortBy, $sortOrder);

    // 📄 Phân trang
    $perPage = $request->perPage ?? 12;
    $products = $query->paginate($perPage);

    
    $wishlistIds = $user
        ? $user->wishlist()->pluck('product_id')->toArray()
        : (session()->get('wishlist', []) ?? []);

    $products->getCollection()->transform(function ($product) use ($wishlistIds) {
        $product->in_wishlist = in_array($product->id, $wishlistIds);
        return $product;
    });

    return response()->json($products);
}

    public function indexForUser(Request $request)
    {
            // Lấy từ khóa tìm kiếm từ query
        $search = $request->input('search');

        return view('user.products.list', [
            'search' => $search, // truyền xuống Blade
        ]);
    }
    public function related(Request $request, $id)
    {
        // Lấy cột từ request
        $column = $request->input('column');

        // Kiểm tra cột có tồn tại trong bảng
        if (!in_array($column, (new Product)->getFillable())) {
            return response()->json([
                'status' => 'error',
                'message' => 'Cột không hợp lệ'
            ], 400);
        }

        // Lấy sản phẩm gốc
        $product = Product::find($id);
        if (!$product) {
            return response()->json([
                'status' => 'error',
                'message' => 'Không tìm thấy sản phẩm'
            ], 404);
        }

        // Lấy giá trị cột
        $value = $product->$column;
        Log::info($value);

        // Lấy danh sách sản phẩm có cùng giá trị (trừ chính nó)
        $related = Product::where($column, $value)
            ->where('id', '!=', $id)
            ->get();

        return response()->json([
            'status' => 'success',
            'column' => $column,
            'value' => $value,
            'related' => $related
        ]);
    }
    public function show(){

    }
    public function export($categoryId = null)
    {
        $cate = Category::findOrFail($categoryId);
        if($cate){
        return Excel::download(new ProductsExport($categoryId), 'products_of_'.$cate->name.'.xlsx');

        }
        return redirect()->back()->with('error','Không tìm thấy danh mục');
    }
    public function sample()
    {
        return Excel::download(new ProductsSampleExport, 'file_mau_san_pham.xlsx');
    }
    
    public function importFile(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv,xls'
        ]);

        try {
            Excel::import(new ProductsImport, $request->file('file'));
            return redirect()->route('products.index')->with('success', 'Import sản phẩm thành công!');
        } catch (\Exception $e) {
            return redirect()->route('products.index')->with('error', 'Lỗi khi import: '.$e->getMessage());
        }
    }

}
