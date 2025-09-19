<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Order;

class PayOSController extends Controller
{
    public function create(Request $request)
{
    $data = $request->validate([
        'order_id' => 'required|integer',
        'amount'   => 'required|numeric|min:1000',
    ]);
    Log::info('PayOS env', [
    'client_id' => config('services.payos.client_id'),
    'api_key' => config('services.payos.api_key'),
    'checksum' => config('services.payos.checksum'),
]);
    $order = Order::findOrFail($data['order_id']);

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
            'message' => 'Vui lòng chọn địa chỉ giao hàng trước khi thanh toán.'
        ], 400);
    }

    $endpoint = "https://api-merchant.payos.vn/v2/payment-requests";
    $clientId = config('services.payos.client_id');
    $apiKey   = config('services.payos.api_key');
    $checksum = config('services.payos.checksum');

    


    $returnUrl = 'http://localhost:8000/order/'.$data['order_id'].'/payos-return';
    $cancelUrl = 'http://localhost:8000/order/'.$data['order_id'].'/payos-cancel';
    $payload = [
            "orderCode"   => (int) substr(time(), -6), // 6 số cuối timestamp
            "amount"      => (int) $data['amount'],
            // "amount"      => 10000,
            "description" => "Thanh toán đơn #" . $data['order_id'],
            
            "returnUrl"   => $returnUrl,
            "cancelUrl"   => $cancelUrl,
        ];

        // Tạo signature (PayOS yêu cầu ký SHA256 HMAC với checksum key)
        $rawData = "amount={$payload['amount']}"
            . "&cancelUrl={$payload['cancelUrl']}"
            . "&description={$payload['description']}"
            . "&orderCode={$payload['orderCode']}"
            . "&returnUrl={$payload['returnUrl']}";

        // Tạo signature
        $signature = hash_hmac('sha256', $rawData, $checksum);
        $payload['signature'] = $signature;
        Log::info('PayOS payload', $payload);

    $response = Http::withHeaders([
    "x-client-id" => $clientId,
    "x-api-key"   => $apiKey,
    "Content-Type" => "application/json",
])->post($endpoint, $payload);

    return response()->json($response->json());
}


    public function webhook(Request $request)
    {
        $data = $request->all();
        Log::info("PayOS webhook:", $data);

        // verify chữ ký
        $checksumKey = env("PAYOS_CHECKSUM_KEY");
        $raw = $data['orderCode'].$data['amount'].$data['status'];
        $signature = hash_hmac("sha256", $raw, $checksumKey);

        if ($signature !== $data['signature']) {
            return response()->json(["message" => "Invalid signature"], 400);
        }

        if ($data['status'] === "SUCCESS") {
            // TODO: cập nhật DB order -> paid
            return response()->json(["message" => "Payment success"]);
        }

        return response()->json(["message" => "Payment failed"]);
    }
    public function return(Request $request, $orderId)
{
    // xem PayOS trả về gì
    Log::info('PayOS return', $request->all());

    $status = $request->get('status'); // PAID, FAILED, ...
    $order  = \App\Models\Order::findOrFail($orderId);
    

    if ($status === 'PAID') {
        $order->update([
            'order_status'   => 1,
            'payment_status' => 1,
        ]);
        return redirect()->route('user.order.show', $orderId)
                         ->with('success', 'Thanh toán thành công!');
    }

    return redirect()->route('user.order.checkout', $orderId)
                     ->with('error', 'Thanh toán thất bại hoặc bị hủy!');
}
    public function cancel(Request $request, $orderId)
    {
        // ⚡ giả sử PayOS redirect kèm query ?status=FAILED&orderCode=xxxx
        $status = $request->get('status');

        $order = \App\Models\Order::findOrFail($orderId);
        Log::info("PayOS cancel:", ['status' => $status, 'order_id' => $orderId]);
        if ($status === "CANCELLED") {
            $order->update([
                'order_status'   => 0, // Chưa xác nhận
                'payment_status' => 0, // Chưa thanh toán
            ]);
        }

        // 🔥 Sau đó redirect sang trang chi tiết đơn hàng
        return redirect()->route('user.order.checkout', $orderId)
                        ->with('error', 'Thanh toán thất bại hoặc bị hủy. Vui lòng thử lại!');
    }
}
