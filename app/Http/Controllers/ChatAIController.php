<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Product; // Giả sử model Product đã tồn tại

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
        $products = Product::orderBy('quantity_buy', 'desc')->take(10)->get();
        $productText = $this->formatProducts($products);

        // === 3. Chuẩn bị System Instruction (Vai trò và Dữ liệu tĩnh) ===
        // Nội dung này sẽ được sử dụng để tạo tin nhắn ngữ cảnh (primer)
        $systemInstructionText = <<<INSTRUCTION
Bạn là trợ lý thông minh và chuyên nghiệp (KHÔNG sử dụng emoji) cho shop manga. 
Phong cách giao tiếp: Nhiệt tình, sử dụng từ ngữ tôn trọng (dùng "bạn" / "chúng tôi"), luôn bắt đầu câu trả lời bằng lời chào hoặc xác nhận yêu cầu.
Nhiệm vụ:
1. Trả lời các câu hỏi về sản phẩm dựa trên Dữ liệu RAG được cung cấp (top 10 sản phẩm bán chạy).
2. Duy trì ngữ cảnh hội thoại dựa trên lịch sử chat.
3. Tuyệt đối không tiết lộ thông tin nội bộ (như số lượng mua, giá trị sale tuyệt đối).
4. Luôn gợi ý mua hàng một cách nhẹ nhàng.

Dưới đây là danh sách 10 sản phẩm bán chạy nhất hiện có của shop:
$productText
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
        $apiKey = env('GEMINI_API_KEY');
        // Sử dụng v1 endpoint mới nhất
        $url = "https://generativelanguage.googleapis.com/v1/models/gemini-2.0-flash:generateContent";

        $data = [
            // Đã gỡ bỏ 'systemInstruction' để tránh lỗi 400
            'contents' => $contents,
            
            // generationConfig được dùng thay cho 'config' cũ
            'generationConfig' => [
                'maxOutputTokens' => 300, 
            ],
        ];

        try {
           $response = Http::withHeaders([
                'Authorization' => "Bearer {$apiKey}",
                'Content-Type' => 'application/json',
            ])->timeout(120)->post($url, $data);
            
            if ($response->failed()) {
                Log::error("Gemini API Error: " . $response->body()); 
                throw new \Exception("Lỗi API Gemini: " . $response->body());
            }

            $jsonResponse = $response->json();
            $text = $jsonResponse['candidates'][0]['content']['parts'][0]['text'] ?? "⚠️ Không có phản hồi từ AI.";

            return $text;

        } catch (\Throwable $e) {
            Log::error("ChatAI callAIGemini - Lỗi: ".$e->getMessage());
            throw new \Exception("Lỗi kết nối hoặc xử lý API.");
        }
    }

    /**
     * Xóa lịch sử chat.
     */
    public function clearHistory(Request $request)
    {
        $request->session()->forget('chat_history');
        return response()->json(['message' => "Đã xóa lịch sử trò chuyện."]);
    }
}
