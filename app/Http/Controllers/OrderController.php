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
    public function index(Request $request)
{
    $query = Order::with('account', 'products');

    // --- Bộ lọc ---
    if ($request->filled('customer_name')) {
        $query->whereHas('account', function ($q) use ($request) {
            $q->where('name', 'like', '%' . $request->customer_name . '%');
        });
    }

    if ($request->filled('status')) {
        $query->where('order_status', $request->status);
    }

    if ($request->order_date) {
    try {
        $date = \Carbon\Carbon::parse($request->order_date)->toDateString();
        $query->whereDate('created_at', '=', $date);
    } catch (\Exception $e) {
        // Nếu ngày không hợp lệ thì bỏ qua
    }
}

 

    // --- Sắp xếp ---
    $sortField = $request->get('sort_field', 'id'); 
    $sortOrder = $request->get('sort_order', 'desc');

    // Cho phép sắp xếp theo: id, total_price, order_status, created_at, customer_name
    if ($sortField === 'customer_name') {
        $query->join('accounts', 'orders.account_id', '=', 'accounts.id')
              ->select('orders.*', 'accounts.name as customer_name')
              ->orderBy('accounts.name', $sortOrder);
    } else {
        $query->orderBy($sortField, $sortOrder);
    }

    $orders = $query->paginate(10);

    // Nếu AJAX thì chỉ trả bảng (render partial view)
    if ($request->ajax()) {
        return view('admin.orders.table', compact('orders'))->render();
    }

    // Nếu load full page
    return view('admin.orders.index', compact('orders'));
}

    public function userOrdersPage()
    {
        return view('user.order.list');
    }
    
    public function listUserOrders(Request $request)
    {
        $user = Auth::user();
        $query = Order::where('account_id', $user->id)->orderBy('created_at', 'desc');;

        // Nếu có filter trạng thái từ query string
        if ($request->has('status')) {
            $query->where('order_status', $request->status);
        }

        $orders = $query->get();

        // Tính số sản phẩm trong mỗi đơn
        $orders->map(function($order) {
            $order->product_count = $order->productOrders()->sum('quantity');
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
        foreach ($order->items as $item) {
                $product = Product::find($item->product_id);

                if ($product) {
                    $product->quantity += $item->quantity;
                    $product->save();
                }
            }

        // 3. Cập nhật trạng thái hủy
        $order->order_status = 5; // 5 = đã hủy
        // $order->updated_by = $user->id;
        $order->save();

        return response()->json([
            'message' => 'Hủy đơn hàng thành công',
            'order_id' => $order->id,
            'order_status' => $order->order_status
        ]);
    }
    public function updateStatus(Request $request, $orderId)
{
    $request->validate([
        'status_want' => 'required|integer|min:0|max:4'
    ]);

    $user = Auth::user();
    $statusWant = $request->status_want;

    // 1. Lấy đơn hàng
    $order = Order::where('id', $orderId)
        ->where('account_id', $user->id)
        ->first();

    if (!$order) {
        return response()->json([
            'message' => 'Đơn hàng không tồn tại hoặc không thuộc bạn'
        ], 404);
    }
    

    $currentStatus = $order->order_status;

    // 2. Kiểm tra điều kiện cập nhật
    if ($statusWant === $currentStatus) {
        return response()->json([
            'message' => 'Trạng thái mới trùng với trạng thái hiện tại'
        ], 400);
    }

    // Chỉ cho phép chuyển từ current → current+1 (theo tuần tự)
    if ($statusWant !== $currentStatus + 1) {
        return response()->json([
            'message' => "Không thể chuyển từ trạng thái {$currentStatus} sang {$statusWant}. 
                          Chỉ có thể chuyển sang trạng thái " . ($currentStatus + 1)
        ], 400);
    }

    // 3. Cập nhật trạng thái
    $order->order_status = $statusWant;
    // $order->updated_by = $user->id;

    $order->save();

    return response()->json([
        'message' => 'Cập nhật trạng thái thành công',
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
    public function userOrderUpdateForm($id)
    {
        $user = Auth::user();
         $order = Order::with(['productOrders.product'])
        ->where('account_id', $user->id)
        ->find($id);

        return view('user.order.update',compact('order'));
    }
public function updateUserOrder(Request $request, $orderId)
{
    $user = Auth::user();
    $order = Order::where('id', $orderId)
                  ->where('account_id', $user->id)
                  ->first();

    if (!$order) {
        return redirect()->back()->with('error', 'Không tìm thấy đơn hàng');
    }

    // chỉ cho phép sửa khi chưa giao
    if (!in_array($order->order_status, [0, 1])) {
                return redirect()->back()->with('error', 'Đơn hàng đã xử lý, không thể chỉnh sửa');

    }

    $data = $request->validate([
        'name_recipient'   => 'required|string|max:100',
        'phone_recipient'  => 'required|string|max:20',
        'shipping_address' => 'required|string|max:255',
        // 'voucher_code'     => 'required|string|max:50',
    ],[
        'name_recipient.required' => 'Tên người nhận không được để trống',
        'phone_recipient.required' => 'Số điện thoại người nhận không được để trống',
        'shipping_address.required' => 'Địa chỉ giao hàng không được để trống',
        // 'voucher_code.required' => 'Mã voucher không được để trống',
    ]);
    if($order->order_status == 0){
        $order->order_status = 1;
    }
    //check giao dịch

    $order->update($data);

    return redirect()->route('user.order.show', ['orderId' => $order->id])->with('success', 'Cập nhật đơn hàng thành công');
}

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        $order = Order::with(['account', 'productOrders.product'])->findOrFail($order->id);

        return view('admin.orders.show', compact('order'));
    }
    public function userShow($id)
    {
        return view('user.order.show', ['id' => $id]);
    }
    public function userShow2($orderId)
{
    $user = Auth::user();

    // Lấy đơn hàng thuộc về user đang đăng nhập
    $order = Order::with(['productOrders.product'])
        ->where('account_id', $user->id)
        ->find($orderId);

    if (!$order) {
        return response()->json([
            'message' => 'Không tìm thấy đơn hàng'
        ], 404);
    }

    return response()->json([
        'order' => $order
    ]);
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
     // API hủy đơn hàng
    public function cancelAdminOrder($orderId)
    {
       

        // 1. Lấy đơn hàng và kiểm tra thuộc user
        $order = Order::where('id', $orderId)
      
            ->first();

        if (!$order) {
            return response()->json([
                'message' => 'Đơn hàng không tồn tại'
            ], 404);
        }

        // 2. Kiểm tra trạng thái: chỉ cho hủy khi order_status = 0 hoặc 1
        if (!in_array($order->order_status, [0, 1])) {
            return response()->json([
                'message' => 'Đơn hàng không thể hủy vì đã xử lý hoặc đang giao'
            ], 400);
        }
        // Hoàn trả tồn kho
        foreach ($order->items as $item) {
                $product = Product::find($item->product_id);

                if ($product) {
                    $product->quantity += $item->quantity;
                    $product->save();
                }
            }
        // 3. Cập nhật trạng thái hủy
        $order->order_status = 5; // 5 = đã hủy
        // $order->updated_by = $user->id;
        $order->save();

        return response()->json([
            'message' => 'Hủy đơn hàng thành công',
            'order_id' => $order->id,
            'order_status' => $order->order_status
        ]);
    }
    public function updateAdminStatus(Request $request, $orderId)
{
    $request->validate([
        'status_want' => 'required|integer|min:0|max:4'
    ]);

   
    $statusWant = $request->status_want;

    // 1. Lấy đơn hàng
    $order = Order::where('id', $orderId)

        ->first();

    if (!$order) {
        return response()->json([
            'message' => 'Đơn hàng không tồn tại hoặc không thuộc bạn'
        ], 404);
    }
   
    $currentStatus = $order->order_status;

    // 2. Kiểm tra điều kiện cập nhật
    if ($statusWant === $currentStatus) {
        return response()->json([
            'message' => 'Trạng thái mới trùng với trạng thái hiện tại'
        ], 400);
    }

    // Chỉ cho phép chuyển từ current → current+1 (theo tuần tự)
    if ($statusWant !== $currentStatus + 1) {
        return response()->json([
            'message' => "Không thể chuyển từ trạng thái {$currentStatus} sang {$statusWant}. 
                          Chỉ có thể chuyển sang trạng thái " . ($currentStatus + 1)
        ], 400);
    }
    if ($statusWant == 1) {
        foreach ($order->products as $product) {
            $quantity = $product->pivot->quantity; // lấy từ bảng trung gian
            $price    = $product->pivot->price;
           
Log::info('Check product', [
                'product_id' => $product->id,
                'quantity_ordered' => $quantity,
                'quantity_in_stock' => $product->quantity
            ]);
    if ($product->quantity < $quantity) {
        return response()->json([
            'message' => "Sản phẩm {$product->name} không đủ hàng trong kho"
        ], 400);
    }

        $product->quantity -= $quantity;
        $product->save();
        }
    }
    // 3. Cập nhật trạng thái
    $order->order_status = $statusWant;
    // $order->updated_by = $user->id;

    $order->save();

    return response()->json([
        'message' => 'Cập nhật trạng thái thành công',
        'order_id' => $order->id,
        'order_status' => $order->order_status
    ]);
}
   
}
