<?php

namespace App\Http\Controllers;

use App\Models\ProductSupplier;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;

class ProductSupplierController extends Controller
{
    public function index()
    {
        $imports = ProductSupplier::with(['product', 'supplier'])->get();
        return view('admin.product_suppliers.index', compact('imports'));
    }

    public function create()
    {
        $products = Product::all();
        $suppliers = Supplier::all();
        return view('admin.product_suppliers.create', compact('products', 'suppliers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id'   => 'required|exists:products,id',
            'supplier_id'  => 'required|exists:suppliers,id',
            
            'import_price' => 'required|numeric',
            'quantity'     => 'required|integer|min:1',
        ]);
        $data = $request->all();
        $data['date_import'] = now();
        ProductSupplier::create($data);

        return redirect()->route('product_suppliers.index')->with('success', 'Thêm nhập hàng thành công');
    }

    public function show($id)
    {
        $import = ProductSupplier::with(['product', 'supplier'])->findOrFail($id);
        return view('admin.product_suppliers.show', compact('import'));
    }

    public function edit($id)
    {
        $import = ProductSupplier::findOrFail($id);
        $products = Product::all();
        $suppliers = Supplier::all();
        return view('admin.product_suppliers.edit', compact('import', 'products', 'suppliers'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'product_id'   => 'required|exists:products,id',
            'supplier_id'  => 'required|exists:suppliers,id',
            'date_import'  => 'required|date',
            'import_price' => 'required|numeric',
            'quantity'     => 'required|integer|min:1',
        ]);

        $import = ProductSupplier::findOrFail($id);
        $import->update($request->all());

        return redirect()->route('product_suppliers.index')->with('success', 'Cập nhật nhập hàng thành công');
    }

    public function destroy($id)
    {
        $import = ProductSupplier::findOrFail($id);
        $import->delete();
        return redirect()->route('product_suppliers.index')->with('success', 'Xóa nhập hàng thành công');
    }
}
