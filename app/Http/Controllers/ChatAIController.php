<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Product;

class ChatAIController extends Controller
{
    public function handle(Request $request)
    {
        $userMessage = $request->input('message');
        Log::info("ChatAI handle() - userMessage: $userMessage");

        // === Lưu lịch sử chat trong session ===
        $chatHistory = $request->session()->get('chat_history', []);
        $chatHistory[] = ['sender'=>'user','text'=>$userMessage];

        // Giới hạn lịch sử chat chỉ 10 tin nhắn cuối
        if(count($chatHistory) > 10) $chatHistory = array_slice($chatHistory, -10);
        $request->session()->put('chat_history', $chatHistory);

        // === Lấy top product bán chạy ===
        $products = Product::orderBy('quantity_buy', 'desc')->take(10)->get();
        $productText = $this->formatProducts($products);

        // === Format lịch sử chat rút gọn ===
        $historyText = "";
        foreach($chatHistory as $m){
            $sender = $m['sender'] === 'user' ? "Người dùng" : "Trợ lý";
            $historyText .= "$sender: {$m['text']}\n";
        }

        // === Tạo prompt ngắn gọn ===
        $prompt = <<<PROMPT
Bạn là trợ lý thông minh cho shop manga.
Dưới đây là lịch sử trò chuyện gần đây:
$historyText
Danh sách sản phẩm hiện có (không để lộ số lượng):
$productText

Người dùng vừa hỏi: "$userMessage"
Hãy trả lời thân thiện, tự nhiên, gợi ý sản phẩm dựa trên danh sách hiện có hoặc lịch sử trò chuyện.
PROMPT;

        try {
            $aiReply = $this->callAI($prompt);
        } catch (\Throwable $e) {
            Log::error("ChatAI handle - Lỗi khi gọi AI: ".$e->getMessage());
            $aiReply = "⚠️ Có lỗi xảy ra, vui lòng thử lại sau.";
        }

        // Thêm câu trả lời bot vào lịch sử
        $chatHistory[] = ['sender'=>'bot','text'=>$aiReply];
        $request->session()->put('chat_history', $chatHistory);

        return response()->json([
            'message' => $aiReply,
            'products' => $products,
        ]);
    }

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

    protected function callAI($prompt)
    {
        $response = Http::timeout(120)->post("http://localhost:11434/api/generate", [
            'model' => 'llama3.1',
            'prompt'=> $prompt,
            'stream'=> false,
            'max_tokens'=> 300 // hạn chế token trả về
        ]);

        $text = $response->json('response');
        return $text ?: "⚠️ Không có phản hồi từ AI.";
    }

    // === Hàm xóa lịch sử chat ===
    public function clearHistory(Request $request)
    {
        $request->session()->forget('chat_history');
        return response()->json(['message'=>"Đã xóa lịch sử trò chuyện."]);
    }
}
