<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\Account;
use App\Models\Product;
use App\Models\ProductOrder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ProductOrderController;
use Illuminate\Support\Facades\Log;


class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::with('account', 'products')->latest()->paginate(10);
        return view('admin.orders.index', compact('orders'));
    }
    public function userOrdersPage()
    {
        return view('user.order.list');
    }
    
    public function listUserOrders(Request $request)
    {
        $user = Auth::user();
        $query = Order::where('account_id', $user->id);

        // Nếu có filter trạng thái từ query string
        if ($request->has('status')) {
            $query->where('order_status', $request->status);
        }

        $orders = $query->get();

        // Tính số sản phẩm trong mỗi đơn
        $orders->map(function($order) {
            $order->product_count = $order->products->count();
            return $order;
        });

        return response()->json([
            'orders' => $orders
        ]);
       
    }
     // API hủy đơn hàng
    public function cancelOrder($orderId)
    {
        $user = Auth::user();

        // 1. Lấy đơn hàng và kiểm tra thuộc user
        $order = Order::where('id', $orderId)
            ->where('account_id', $user->id)
            ->first();

        if (!$order) {
            return response()->json([
                'message' => 'Đơn hàng không tồn tại hoặc không thuộc bạn'
            ], 404);
        }

        // 2. Kiểm tra trạng thái: chỉ cho hủy khi order_status = 0 hoặc 1
        if (!in_array($order->order_status, [0, 1])) {
            return response()->json([
                'message' => 'Đơn hàng không thể hủy vì đã xử lý hoặc đang giao'
            ], 400);
        }

        // 3. Cập nhật trạng thái hủy
        $order->order_status = 4; // 4 = đã hủy
        $order->save();

        return response()->json([
            'message' => 'Hủy đơn hàng thành công',
            'order_id' => $order->id,
            'order_status' => $order->order_status
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('user.orders.create');
    }
    // public function add(Request $request){
    //      $request->validate([
    //         'products' => 'required|array|min:1',
    //         'products.*.product_id' => 'required|integer|exists:products,id',
    //         'products.*.quantity' => 'required|integer|min:1',
    //         'products.*.price' => 'required|numeric|min:0',
    //         'total_price' => 'required|numeric|min:0',
    //     ]);
    //     $user = Auth::user();
       
    //     DB::beginTransaction();
    //     try {
    //         $order = Order::create([
    //         'account_id' => $user->id, // giả sử bạn có auth
    //         'order_status' => 0,
    //         'total_price' => $request->total_price       
    //     ]);
    //     }catch (\Exception $e) {
    //         DB::rollBack();
    //         return response()->json([
    //             'message' => 'Error creating order',
    //             'error' => $e->getMessage()
    //         ], 500);
    //     }
    //     DB::commit();
       
    // }
    public function add(Request $request)
    {
        $request->validate([
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|integer|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.price' => 'required|numeric|min:0',
            'total_price' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            // 1. Tạo order
            $order = Order::create([
                'account_id' => Auth::user()->id, // giả sử bạn có auth
                'status' => 0,
                'total_price' => $request->total_price       
            ]);

            $productOrderController = new ProductOrderController();

            // 2. Lặp từng sản phẩm và gọi add()
            foreach ($request->products as $product) {
                $productOrderController->add($product, $order->id);
            }


            DB::commit();

            return response()->json([
                'message' => 'Order created successfully',
                'order_id' => $order->id
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
    //         Log::error('Lỗi tạo order: ' . $e->getMessage(), [
    //     'products' => $request->products,
    //     'user_id' => Auth::id() ?? null
    // ]);
            return response()->json([
                'message' => 'Error creating order',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        $order->load('account', 'products');
        return view('orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        $accounts = Account::all();
        $products = Product::all();
        $order->load('products');
        return view('orders.edit', compact('order', 'accounts', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        // Chỉ validate và lấy đúng cột cần
        $validated = $request->validate([
            'order_status' => 'required|string|in:pending,processing,completed,cancelled',
        ]); 

        // Cập nhật chỉ riêng status
        $order->update([
            'order_status' => $validated['order_status'],
        ]);

        return redirect()->route('orders.index')->with('success', 'Cập nhật trạng thái đơn hàng thành công');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }
}
