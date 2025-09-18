<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shipping;

class ShippingController extends Controller
{
    public function index(Request $request)
    {
        return Shipping::where('account_id', $request->user()->id)->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name_recipient'   => 'required|string|max:100',
            'phone_recipient'  => 'required|string|max:20',
            'shipping_address' => 'required|string|max:255',
            // 'shipping_fee'     => 'required|numeric|min:0',
        ]);
        $shippingAddressLower = mb_strtolower($data['shipping_address']); // chuyển về lowercase
        // if (str_contains($shippingAddressLower, 'hà nội')) {
        //     $data['shipping_fee'] = 50000;
        // } else {
        //     $data['shipping_fee'] = 100000; // mặc định cho các tỉnh khác
        // }

        $data['account_id'] = $request->user()->id;
        $shipping = Shipping::create($data);

        return response()->json($shipping, 201);
    }
     public function update(Request $request, $id)
    {
        $shipping = Shipping::findOrFail($id);

        $data = $request->validate([
            'name_recipient'   => 'required|string|max:255',
            'phone_recipient'  => 'required|string|max:20',
            'shipping_address' => 'required|string',
            // 'shipping_fee'     => 'nullable|numeric'
        ]);
        // $shippingAddressLower = mb_strtolower($data['shipping_address']); // chuyển về lowercase
        // if (str_contains($shippingAddressLower, 'hà nội')) {
        //     $data['shipping_fee'] = 50000;
        // } else {
        //     $data['shipping_fee'] = 100000; // mặc định cho các tỉnh khác
        // }

        $shipping->update($data);

        return response()->json($shipping);
    }
    public function delete(Request $request, $id)
    {
        // Lấy địa chỉ theo id
        $shipping = Shipping::find($id);

        if (!$shipping) {
            return response()->json([
                'message' => 'Địa chỉ không tồn tại'
            ], 404);
        }

        // Kiểm tra account_id (nếu muốn bảo vệ không xóa nhầm)
        if ($shipping->account_id != $request->user()->id) {
            return response()->json([
                'message' => 'Bạn không có quyền xóa địa chỉ này'
            ], 403);
        }

        // Xóa địa chỉ
        $shipping->delete();

        return response()->json([
            'message' => 'Xóa địa chỉ thành công',
            'id' => $id
        ]);
    }
}
