<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Product; // Giả sử model Product đã tồn tại
use App\Models\Voucher;
use Illuminate\Support\Facades\Auth;


class ChatAIController extends Controller
{
    /**
     * Xử lý tin nhắn chat từ người dùng, tích hợp dữ liệu sản phẩm.
     */
    public function handle(Request $request)
    {
        $userMessage = $request->input('message');
        Log::info("ChatAI handle() - userMessage: $userMessage");

        // === 1. Lấy lịch sử chat và chuyển đổi sang cấu trúc Contents ===
        $chatHistory = $request->session()->get('chat_history', []);
        
        $contents = [];
        // Chuyển đổi lịch sử chat sang định dạng Gemini API
        foreach ($chatHistory as $m) {
            $role = $m['sender'] === 'user' ? 'user' : 'model';
            $contents[] = [
                'role' => $role,
                'parts' => [['text' => $m['text']]]
            ];
        }

        // === 2. Lấy dữ liệu sản phẩm (RAG Data) ===
        $products = Product::orderBy('quantity_buy', 'desc')->take(25)->get();
        $vouchers = Voucher::where('is_active', 1)
            ->whereDate('date_end', '>=', now())
            ->get();
        $productText = $this->formatProducts($products);
       
        

        // === 3. Chuẩn bị System Instruction (Vai trò và Dữ liệu tĩnh) ===
        // Nội dung này sẽ được sử dụng để tạo tin nhắn ngữ cảnh (primer)
        $systemInstructionText = <<<INSTRUCTION
Bạn là trợ lý thông minh (nữ - 18 tuổi) và chuyên nghiệp (KHÔNG sử dụng emoji) cho MangaShop. 
Phong cách giao tiếp: Nhiệt tình, sử dụng từ ngữ tự nhiên phù hợp với người đam mê manga tại việt nam, bắt đầu câu trả lời bằng 1 cái gì đó vui tươi ngộ nghĩnh(đừng chào nhiều quá)
Nhiệm vụ:
1. Trả lời các câu hỏi về sản phẩm dựa trên Dữ liệu RAG được cung cấp (top  sản phẩm bán chạy).
2. Duy trì ngữ cảnh hội thoại dựa trên lịch sử chat.
3. Tuyệt đối không tiết lộ thông tin nội bộ (như số lượng mua, giá trị sale tuyệt đối).
4. Luôn gợi ý mua hàng một cách nhẹ nhàng.

Dưới đây là danh sách sản phẩm bán chạy nhất hiện có của shop:
$productText
Và đây là danh sách voucher(mã giảm giá, mã khuyến mãi) của shop:
$vouchers



INSTRUCTION;
        
        // === 4. Gắn System Instruction vào đầu Contents (Primer Message) ===
        $primerMessage = [
            'role' => 'user', 
            'parts' => [['text' => $systemInstructionText]]
        ];
        
        // Chèn Primer Message vào đầu mảng contents
        array_unshift($contents, $primerMessage);

        // Thêm nội dung của lượt chat hiện tại vào Contents
        $contents[] = ['role' => 'user', 'parts' => [['text' => $userMessage]]];

        try {
            // Gọi hàm API (không truyền systemInstruction riêng nữa)
            $aiReply = $this->callAIGemini($contents);
        } catch (\Throwable $e) {
            Log::error("ChatAI handle - Lỗi khi gọi AI: " . $e->getMessage());
            $aiReply = "⚠️ Có lỗi xảy ra trong quá trình gọi AI, vui lòng thử lại sau.";
        }

        // === 5. Lưu lịch sử chat (cho session) ===
        $chatHistory[] = ['sender' => 'user', 'text' => $userMessage];
        $chatHistory[] = ['sender' => 'bot', 'text' => $aiReply];
        
        // Loại bỏ Primer Message (chỉ dùng cho API call, không lưu vào session)
        if (count($chatHistory) > 10) $chatHistory = array_slice($chatHistory, -10);
        
        $request->session()->put('chat_history', $chatHistory);

        return response()->json([
            'message' => $aiReply,
            'products' => $products,
        ]);
    }

    /**
     * Định dạng dữ liệu sản phẩm thành chuỗi văn bản cho AI.
     */
    protected function formatProducts($products)
    {
        $items = [];
        foreach ($products as $p) {
            $name = $p->name ?? $p->title ?? "Unknown";
            $price = isset($p->price) ? number_format($p->price, 0, ',', '.') . "₫" : "Giá không có";
            $author = $p->author ?? "Tác giả không có";
            $genre = $p->categ ?? "Thể loại không có";
            $sale = $p->sale ?? "Không có giảm giá";

            $items[] = "Tên: $name | Giá: $price | Sale: $sale | Tác giả: $author | Thể loại: $genre";
        }
        return implode("\n", $items);
    }

    /**
     * Hàm gọi API chính đến Gemini. Đảm bảo cấu trúc JSON đúng.
     */
    protected function callAIGemini($contents)
{
    $apiKey = config('services.gemeni.key');
    // Dùng v1beta và model hỗ trợ
    $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key={$apiKey}";

    $data = [
        'contents' => $contents,
        'generationConfig' => [
            'maxOutputTokens' => 300,
        ],
    ];

    $response = Http::timeout(60)
        ->withHeaders(['Content-Type' => 'application/json'])
        ->post($url, $data);

    if ($response->failed()) {
        Log::error("Gemini API Error: " . $response->body());
        throw new \Exception("Lỗi API Gemini: " . $response->body());
    }

    $jsonResponse = $response->json();
    $text = $jsonResponse['candidates'][0]['content']['parts'][0]['text'] 
        ?? "⚠️ Không có phản hồi từ AI.";

    return $text;
}


    /**
     * Xóa lịch sử chat.
     */
    public function clearHistory(Request $request)
    {
        $request->session()->forget('chat_history');
        return response()->json(['message' => "Đã xóa lịch sử trò chuyện."]);
    }
    protected function formatVouchers($vouchers)
    {
        $items = [];

        foreach ($vouchers as $v) {
            $code = $v->code ?? 'Không có mã';
            $sale = isset($v->sale) ? $v->sale . '%' : 'Không có giảm giá';
        
            $dateEnd = isset($v->date_end) ? date('d/m/Y', strtotime($v->date_end)) : 'Không có ngày hết hạn';
            $maxDiscount = isset($v->max_discount) ? number_format($v->max_discount, 0, ',', '.') . '₫' : 'Không giới hạn';

            $items[] = "🎟️ Mã: {$code} | Giảm: {$sale} | Tối đa: {$maxDiscount}  | Hạn dùng: {$dateEnd}";
        }

        return implode("\n", $items);
    }
    protected function formatOrders($orders)
{
    // Map trạng thái đơn hàng
    $orderStatuses = [
        0 => "🕓 Chờ khách xác nhận đơn",
        1 => "📦 Đang xử lý",
        2 => "🚚 Đang giao",
        3 => "✅ Hoàn tất",
        4 => "🔁 Đổi trả",
        5 => "❌ Đã hủy",
        6 => "💸 Hoàn tiền",
    ];

    // Map phương thức thanh toán
    $paymentStatuses = [
        0 => "Thanh toán khi nhận hàng (COD)",
        1 => "Thanh toán online",
    ];

    $items = [];
    foreach ($orders as $o) {
        $orderDate = isset($o->order_date) ? date('d/m/Y', strtotime($o->order_date)) : 'Không rõ ngày đặt';
        $deliverDate = isset($o->deliver_date) ? date('d/m/Y', strtotime($o->deliver_date)) : 'Chưa giao';

        $status = $orderStatuses[$o->order_status] ?? 'Không rõ trạng thái';
        $payment = $paymentStatuses[$o->payment_status] ?? 'Không rõ thanh toán';

        $subtotal = isset($o->subtotal_price) ? number_format($o->subtotal_price, 0, ',', '.') . '₫' : '0₫';
        $total = isset($o->total_price) ? number_format($o->total_price, 0, ',', '.') . '₫' : '0₫';

        $voucher = $o->voucher_id ? "Có dùng voucher (ID: {$o->voucher_id})" : "Không dùng voucher";
        $shipping = $o->shipping_id ? "Mã vận chuyển: {$o->shipping_id}" : "Chưa có vận chuyển";

        $items[] = "Mã đơn: $o->id| Ngày đặt: $orderDate  | Trạng thái: $status | Thanh toán: $payment | Tổng: $total | Tạm tính: $subtotal ";
    }

    return implode("\n", $items);
}
}
