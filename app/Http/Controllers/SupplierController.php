<?php

namespace App\Http\Controllers;

use App\Http\Requests\SupplierRequest;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class SupplierController extends Controller
{
     public function index()
    {
        $suppliers = Supplier::all();
        return view('admin.suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        return view('admin.suppliers.create');
    }

    public function store(SupplierRequest $request)
    {
        $validated = $request->validated();


        if ($request->hasFile('contract')) {
            $validated['contract'] = $request->file('contract')->store('contracts', 'public');
        }

        Supplier::create($validated);

        return redirect()->route('suppliers.index')->with('success', 'Thêm nhà cung cấp thành công!');
    }

    public function show(Supplier $supplier)
    {
        $products = $supplier->productSuppliers()
                         ->with('importBy','product')
                         ->orderBy('created_at','desc')
                         ->paginate(10); // 10 bản ghi / trang

          return view('admin.suppliers.show', compact('supplier', 'products'));
    }
    public function filterProducts(Request $request, Supplier $supplier)
    {
        $query = $supplier->productSuppliers()->with('importBy','product');

        // $query = $supplier->productSuppliers()->with('importBy','product');

        if($request->filled('name')) {
            $query->whereHas('product', function($q) use ($request) {
                $q->where('name', 'like', '%'.$request->name.'%')
                // ->orWhere('name', 'like', '%'.$request->name.'%')
                ;
            });
        }

        if($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $products = $query->orderBy('created_at','desc')->paginate(10);

        // Trả về partial kèm $supplier
        return view('admin.suppliers.products_table', compact('products','supplier'))->render();
    }

    public function edit(Supplier $supplier)
    {
        return view('admin.suppliers.edit', compact('supplier'));
    }

    public function update(SupplierRequest $request, Supplier $supplier)
    {
        $validated = $request->validated();


        if ($request->hasFile('contract')) {
            // Xóa file cũ nếu có
            if ($supplier->contract && Storage::disk('public')->exists($supplier->contract)) {
                Storage::disk('public')->delete($supplier->contract);
            }
            $validated['contract'] = $request->file('contract')->store('contracts', 'public');
        }

        $supplier->update($validated);

        return redirect()->route('suppliers.index')->with('success', 'Cập nhật nhà cung cấp thành công!');
    }

    public function destroy(Supplier $supplier)
    {
        if ($supplier->contract && Storage::disk('public')->exists($supplier->contract)) {
            Storage::disk('public')->delete($supplier->contract);
        }

        $supplier->delete();

        return redirect()->route('suppliers.index')->with('success', 'Xóa nhà cung cấp thành công!');
    }
    public function active(Supplier $supplier)
    {
        if($supplier->is_active==1){
            $supplier->update(['is_active' => 0]);
        
        // Optional: redirect hoặc trả về JSON
             return redirect()->back()->with('success', 'Nhà cung cấp đã bị vô hiệu hóa.');
        }
        $supplier->update(['is_active' => 1]);
        // Optional: redirect hoặc trả về JSON
        return redirect()->back()->with('success', 'Nhà cung cấp đã mở lại.');
        
    }
}
