<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\Account;
use App\Models\Product;
use App\Models\ProductOrder;
use App\Models\Shipping;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ProductOrderController;
use App\Models\Voucher;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;


class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Order::with('account', 'products');
        $perPage = $request->get('per_page', 10);
        // --- Bộ lọc ---
        if ($request->filled('customer_name')) {
            $query->whereHas('account', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->customer_name . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('order_status', $request->status);
        }
        // Bộ lọc theo select-fill (tuần, tháng, năm...)
        if ($request->filled('select_fill')) {
            switch ($request->input('select_fill')) {
                case 'week':
                    $query->whereBetween('created_at', [
                        now()->startOfWeek(), now()->endOfWeek()
                    ]);
                    break;
                case 'lastWeek':
                    $query->whereBetween('created_at', [
                        now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek()
                    ]);
                    break;
                case 'month':
                    $query->whereMonth('created_at', now()->month)
                        ->whereYear('created_at', now()->year);
                    break;
                case 'lastMonth':
                    $lastMonth = now()->subMonth();
                    $query->whereMonth('created_at', $lastMonth->month)
                        ->whereYear('created_at', $lastMonth->year);
                    break;
                case 'year':
                    $query->whereYear('created_at', now()->year);
                    break;
                case 'lastYear':
                    $query->whereYear('created_at', now()->subYear()->year);
                    break;
            }
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

        $orders = $query->with('updatedBy')->paginate($perPage)->withQueryString();

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
        // foreach ($order->products as $product) {
        //         $quantity = $product->pivot->quantity; // lấy từ bảng trung gian

        //         if ($product) {
        //             if($order->order_status == 1){
        //                 $product->cancel($quantity);
        //             }
                    
        //             $product->save();
        //         }
        //     }

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
    public function recallOrder($orderId)
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
        
        // foreach ($order->products as $product) {
        //         $quantity = $product->pivot->quantity; // lấy từ bảng trung gian

        //         if ($product) {
        //             if($order->order_status == 1){
        //                 $product->cancel($quantity);
        //             }
                    
        //             $product->save();
        //         }
        //     }

        // 3. Cập nhật trạng thái hủy
        if($order->order_status == 5){
            $order->order_status = 0; 
                    // $order->updated_by = $user->id;
            $order->save();
            return response()->json(['message'=>'Thành công']);
        }
        return response()->json(['message'=>'Thất bại']);
        
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
            'voucher' => 'nullable|string|max:50',
            'shipping_id' => 'nullable|integer|exists:shippings,id'
        ]);

        $user = Auth::user();

        $voucher_id = $request->voucher ? Voucher::where('code', $request->voucher)->value('id') : null;
        
        


        DB::beginTransaction();
        try {
            // 1. Tạo order
            $order = Order::create([
                'account_id' => Auth::user()->id, // giả sử bạn có auth
                'status' => 0,
                // 'total_price' => $request->total_price,
                'subtotal_price' => $request->subtotal_price,
                'voucher_id' => $voucher_id ?? null,
                'shipping_id' => $request->shipping_id ?? null,
            ]);

            $productOrderController = new ProductOrderController();
            $cartController = new CartController();

            // 2. Lặp từng sản phẩm và gọi add()
            foreach ($request->products as $product) {
                $productOrderController->add($product, $order->id);
                $cartController->removeV2($product,$user->id);
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
    Log::error('❌ Lỗi tạo order: ' . $e->getMessage(), [
        'products' => $request->products ?? null,
        'user_id'  => Auth::id() ?? null,
        'trace'    => $e->getTraceAsString(), // nếu muốn debug sâu hơn
    ]);
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
        $user  = Auth::user();
        $order = Order::where('id', $id)
                  ->where('account_id', $user->id)
                  ->first();
        if(!$order){
            return redirect()->route('user.order.list')
                             ->with('error', 'Không tìm thấy đơn hàng');
        }
        if($order->order_status != 0){
            return redirect()->route('user.order.show', $id)
                             ->with('error', 'Đơn hàng đã xử lý, không thể chỉnh sửa');
        }
        return view('user.order.checkout', ['id' => $id,'account_id' => $user->id]);
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
        // 'total_price'     => 'nullable|numeric|min:0',
    ],[
        'name_recipient.required' => 'Tên người nhận không được để trống',
        'phone_recipient.required' => 'Số điện thoại người nhận không được để trống',
        'shipping_address.required' => 'Địa chỉ giao hàng không được để trống',
        // 'voucher_code.required' => 'Mã voucher không được để trống',
    ]);

// Phí ship (ví dụ theo địa chỉ)
$shipping_fee = \Illuminate\Support\Str::contains($request->shipping_address, 'Hà Nội') ? 50000 : 100000;

// Tổng cuối cùng


// Gán vào $data để update

$data['shipping_fee'] = $shipping_fee;
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
        $order = Order::with(['account', 'productOrders.product','voucher','shipping'])->findOrFail($order->id);
        // $order->load('voucher');
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
    $order = Order::with(['productOrders.product','voucher', 'shipping'])
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
        // foreach ($order->products as $product) {
        //         $quantity = $product->pivot->quantity; // lấy từ bảng trung gian

        //         if ($product) {
        //             if($order->order_status == 1){
        //                 $product->cancel($quantity);
        //             }
                    
        //             $product->save();
        //         }
        //     }
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
    Log::info('Update admin status', [
        'order_id' => $orderId,
        'current_status' => $currentStatus,
        'status_want' => $statusWant
    ]);
    if ($statusWant == 2) {
        $currentUser = Auth::user();
        $orderOwner = $order->account;

        // Chặn tự xác nhận
        if ($currentUser->id == $orderOwner->id) {
            return response()->json([
                'message' => "Bạn không thể xác nhận đơn của chính bạn!"
            ], 400);
        }
        if ($currentUser->role != 0 && $currentUser->role >= $orderOwner->role) {
            return response()->json([
                'message' => "Bạn không có quyền xác nhận đơn này!"
            ], 400);
        }
        foreach ($order->products as $product) {
            $quantity = $product->pivot->quantity; // lấy từ bảng trung gian
            $price    = $product->pivot->price;
            if ($product->quantity < $quantity) {
                return response()->json([
                    'message' => "Sản phẩm {$product->name} không đủ hàng trong kho"
                ], 400);
            }

                $product->buy($quantity);
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
// app/Http/Controllers/OrderController.php
public function showOrder($order_id)
{
    $user = Auth::user();

    $order = Order::with(['shipping', 'productOrders.product', 'voucher'])
                  ->where('id', $order_id)
                  ->where('account_id', $user->id)
                  ->first();

    if (!$order) {
        return response()->json(['message' => 'Không tìm thấy đơn hàng'], 404);
    }

    return response()->json([

        'id' => $order->id,
        'shipping' => $order->shipping,
        'products' => $order->products,
        'voucher' => $order->voucher,
        'payment_status' => $order->payment_status,
    ]);
}
 // Tạo QR thanh toán MoMo Dev
    public function momoDevPay(Request $request)
    {
       

        // Giả lập URL thanh toán (sandbox/dev)
         $data = $request->validate([
            'order_id' => 'required|integer',
            'amount'   => 'required|numeric|min:1000',
        ]);

        // Thông tin tài khoản nhận
        $bankCode    = "970422"; // MB Bank BIN code
        $accountNo   = "0849838298"; // số tài khoản của bạn
        $accountName = "NGUYEN PHUONG NAM"; // tên chủ TK (viết hoa không dấu)

        $orderId = $data['order_id'];
        $amount  = (int) $data['amount'];
        // $amount  = (int) ();
        $addInfo = "Thanh toan don $orderId"; // nội dung CK

        // API VietQR miễn phí từ VietQR.io
        $qrUrl = "https://img.vietqr.io/image/{$bankCode}-{$accountNo}-compact2.png?amount={$amount}&addInfo=" . urlencode($addInfo) . "&accountName=" . urlencode($accountName);

        return response()->json([
            'qr_url'   => $qrUrl,
            'order_id' => $orderId,
            'amount'   => $amount,
            'add_info' => $addInfo,
        ]);

       
    }
    public function momoDevCreate(Request $request)
    {
        $data = $request->validate([
            'order_id' => 'required|integer',
            'amount' => 'required|numeric|min:1000',
            'order_info' => 'required|string',
        ]);

        $endpoint = 'https://test-payment.momo.vn/v2/gateway/api/create';
        $partnerCode = 'MOMOBKUN20180529';
        $accessKey = 'klm05TvNBzhg7h7j';
        $secretKey = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';
        $orderInfo = "Thanh toán qua ATM MoMo";

        $orderId = $data['order_id'] . '-' . Str::random(6);
        $requestId = Str::uuid();
        $amount = $data['amount'];
        $orderInfo = $data['order_info'];
        $returnUrl = env('APP_URL') . "/order/".$orderId;
        $notifyUrl = env('APP_URL') . "/order/".$orderId;
        // $ipnUrl = "http://localhost:8080/order/";


        // Chuỗi để tạo chữ ký
        $rawHash = "accessKey=$accessKey&amount=$amount&extraData=&ipnUrl=$notifyUrl&orderId=$orderId&orderInfo=$orderInfo&partnerCode=$partnerCode&redirectUrl=$returnUrl&requestId=$requestId&requestType=captureWallet";
        $signature = hash_hmac('sha256', $rawHash, $secretKey);

        $response = Http::post($endpoint, [
            'partnerCode' => $partnerCode,
            'accessKey'   => $accessKey,
            'requestId'   => $requestId,
            'amount'      => $amount,
            'orderId'     => $orderId,
            'orderInfo'   => $orderInfo,
            'returnUrl'   => $returnUrl,
            'notifyUrl'   => $notifyUrl,
            'extraData'   => '',
            'requestType' => 'captureWallet',
            'signature'   => $signature,
        ]);

        $res = $response->json();
        Log::info('MoMo response:', $res);
        return response()->json([
            'payUrl'   => $res['payUrl'] ?? null,
            'qrCodeUrl'=> $res['qrCodeUrl'] ?? null,
            'raw'      => $res,
        ]);
    }

    // Xác nhận thanh toán
    public function momoDevConfirm(Request $request)
    {
        $data = $request->validate([
            'order_id' => 'required|integer',
        ]);

        $order = Order::find($data['order_id']);
        if (!$order) {
            return response()->json(['message'=>'Đơn hàng không tồn tại'], 404);
        }
         // Nếu đang là trạng thái chưa thanh toán
        if ($order->payment_status == 0) {
            // Dev mode: giả lập đã thanh toán thành công
            $order->payment_status = 1; // 1 = đã thanh toán
            $order->payment_method = 'MoMo Sandbox';
            $order->save();
        }

        

        return response()->json(['message'=>'Thanh toán MoMo Dev thành công', 'order'=>$order]);
    }
    public function applyVoucher(Request $request, $orderId)
    {
        $request->validate([
            'voucher_code' => 'required|string'
        ]);

        $order = Order::findOrFail($orderId);
        Log::info($orderId);

        $voucher = Voucher::where('code', $request->voucher_code)
    ->where('is_active', 1)
    ->where(function($q) {
        $q->whereNull('date_end') // nếu chưa set ngày hết hạn
          ->orWhere('date_end', '>=', now()); // hoặc còn hạn sử dụng
    })
    ->first();

        if (!$voucher) {
            return response()->json([
                'success' => false,
                'message' => 'Voucher không hợp lệ hoặc đã hết hạn.'
            ]);
        }

        // Cập nhật voucher vào order (chưa tính tiền)
        $order->voucher_id = $voucher->id;
        $order->save();

        return response()->json([
            'success' => true,
            'voucher' => [
                'id'          => $voucher->id,
                'code'        => $voucher->code,
                'sale'        => $voucher->sale,         // % giảm
                'max_discount'=> $voucher->max_discount  // tối đa
            ]
        ]);
    }
    public function codConfirm(Request $request){
        $request->validate([
            'order_id' => 'required|integer|exists:orders,id',
        ]);

        // Lấy đơn hàng
        $order = Order::findOrFail($request->order_id);

        // Kiểm tra trạng thái hiện tại
        if ($order->order_status != 0) {
            return response()->json([
                'success' => false,
                'message' => 'Đơn hàng đã được xử lý hoặc không hợp lệ.'
            ], 400);
        }
        // Kiểm tra xem đã có địa chỉ giao hàng chưa
        if (!$order->shipping_id) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng chọn địa chỉ giao hàng trước khi xác nhận COD.'
            ], 400);
        }

        // Cập nhật trạng thái sang "Đang xử lý"
        $order->order_status = 1;
        // $order->order_date =now();
        $order->save();

        return response()->json([
            'success' => true,
            'message' => 'Đơn hàng đã xác nhận COD.',
            'order' => $order
        ]);
    }
    public function updateShipping(Request $request, Order $order)
{
    $request->validate([
        'shipping_id' => 'required|exists:shippings,id',
    ]);

    $order->shipping_id = $request->shipping_id;
    $order->save();

    return response()->json([
        'success' => true,
        'shipping_id' => $order->shipping_id
    ]);
}
    public function showChart(){
        return view('admin.orders.chart');
    }
    public function countStatus() {
        $userId = Auth::user()->id;

        // Nhóm theo trạng thái và đếm số lượng
        $stats = Order::where('account_id', $userId)
            ->selectRaw('order_status, COUNT(*) as total')
            ->groupBy('order_status')
            ->pluck('total', 'order_status'); // trả về mảng key = status, value = total

        // Đảm bảo có đủ tất cả trạng thái (0 -> 5), nếu thiếu thì set = 0
        $statuses = [0, 1, 2, 3, 4, 5];
        $result = [];
        foreach ($statuses as $status) {
            $result[$status] = $stats[$status] ?? 0;
        }

        return response()->json([
            'user_id' => $userId,
            'counts'  => $result
        ]);
    }
   
}
