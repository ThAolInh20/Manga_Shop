<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vouchers = Voucher::latest()->paginate(10);
        return view('admin.vouchers.index', compact('vouchers'));
    }
    public function listActiveVouchers()
    {
        $vouchers = Voucher::where('is_active', true)
                            ->where(function ($query) {
                                $query->whereNull('date_end')
                                      ->orWhere('date_end', '>', now());
                            })
                            ->get();
        return response()->json($vouchers);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.vouchers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

          $request->validate([
            'code' => 'required|unique:vouchers,code',
            'sale' => 'required|numeric|min:0|max:100',
            'is_active' => 'required|boolean',
            'date_end' => 'nullable|date',
            'max_discount' => 'nullable|numeric|min:0'
        ],[
            'code.required'      => '⚠️ Mã voucher không được để trống',
        'code.unique'        => '⚠️ Mã voucher đã tồn tại, vui lòng nhập mã khác',

        'sale.required'      => '⚠️ Vui lòng nhập phần trăm giảm giá',
        'sale.numeric'       => '⚠️ Giá trị giảm giá phải là số',
        'sale.min'           => '⚠️ Giảm giá không được nhỏ hơn 0',
        'sale.max'           => '⚠️ Giảm giá không được vượt quá 100%',

        'is_active.required' => '⚠️ Trạng thái bắt buộc chọn',
        'is_active.boolean'  => '⚠️ Trạng thái không hợp lệ',

        'date_end.date'      => '⚠️ Ngày kết thúc không hợp lệ (phải đúng định dạng date)',

        'max_discount.numeric' => '⚠️ Giá trị giảm tối đa phải là số',
        'max_discount.min'     => '⚠️ Giá trị giảm tối đa không được nhỏ hơn 0',

        ]);

        Voucher::create($request->all());

        return redirect()->route('vouchers.index')->with('success', 'Thêm voucher thành công!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Voucher $voucher)
    {
        return view('admin.vouchers.show', compact('voucher'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Voucher $voucher)
    {
         return view('admin.vouchers.edit', compact('voucher'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Voucher $voucher)
    {
        $request->validate([
            'code' => 'required|unique:vouchers,code,' . $voucher->id,
            'sale' => 'required|numeric|min:0|max:100',
            'is_active' => 'required|boolean',
            'date_end' => 'nullable|date',
            'max_discount' => 'nullable|numeric|min:0'
        ],[
            'code.required'      => '⚠️ Mã voucher không được để trống',
        'code.unique'        => '⚠️ Mã voucher đã tồn tại, vui lòng nhập mã khác',

        'sale.required'      => '⚠️ Vui lòng nhập phần trăm giảm giá',
        'sale.numeric'       => '⚠️ Giá trị giảm giá phải là số',
        'sale.min'           => '⚠️ Giảm giá không được nhỏ hơn 0',
        'sale.max'           => '⚠️ Giảm giá không được vượt quá 100%',

        'is_active.required' => '⚠️ Trạng thái bắt buộc chọn',
        'is_active.boolean'  => '⚠️ Trạng thái không hợp lệ',

        'date_end.date'      => '⚠️ Ngày kết thúc không hợp lệ (phải đúng định dạng date)',

        'max_discount.numeric' => '⚠️ Giá trị giảm tối đa phải là số',
        'max_discount.min'     => '⚠️ Giá trị giảm tối đa không được nhỏ hơn 0',
        ]
    );

        $voucher->update($request->all());

        return redirect()->route('vouchers.index')->with('success', 'Cập nhật voucher thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Voucher $voucher)
    {
        $voucher->delete();
        return redirect()->route('vouchers.index')->with('success', 'Xóa voucher thành công!');
    }
}
