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
            'shipping_fee'     => 'required|numeric|min:0',
        ]);

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
            'shipping_fee'     => 'nullable|numeric'
        ]);

        $shipping->update($data);

        return response()->json($shipping);
    }
}
