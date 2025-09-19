<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;



class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    public $timestamps = true;

    protected $fillable = [
        'account_id', 'order_date', 'deliver_date', 'order_status', 
        // 'shipping_fee', 'shipping_address', 'phone_recipient','name_recipient',
        'total_price', 'payment_status', 'voucher_id','subtotal_price','shipping_id','update_by'
    ];
//     public function calculateTotalPrice()
// {
//     // tổng tiền sản phẩm
//     $subtotal = $this->products->sum(function ($product) {
//         return $product->pivot->subtotal_price;
//     });

//     // mặc định ko có giảm giá
//     $discount = 0;

//     // nếu có voucher
//     if ($this->voucher) {
//         // giả sử voucher có cột 'discount_percent' (vd: 10%)
//         $discount = $subtotal * ($this->voucher->discount_percent / 100);
//     }

//     // công thức
//     return $subtotal - $discount + $this->shipping_fee;
// }
    public static function countPending()
        {
            return static::where('order_status', 1)->count();
        }
    public static function topAccountByOrders()
{
    return static::join('accounts', 'orders.account_id', '=', 'accounts.id')
        ->select('accounts.id', 'accounts.name', DB::raw('COUNT(orders.id) as total'))
        ->where('order_status',3)
        ->groupBy('accounts.id', 'accounts.name')
        ->orderByDesc('total')
        ->first();
}

public static function topAccountByRevenue()
{
    return static::join('accounts', 'orders.account_id', '=', 'accounts.id')
        ->select('accounts.id', 'accounts.name', DB::raw('SUM(orders.total_price) as total'))
        ->groupBy('accounts.id', 'accounts.name')
        ->orderByDesc('total')
        ->first();
}



    protected static function booted()
        {
            static::saving(function ($order) {
                $subtotal = $order->subtotal_price ?? 0;
                $discount = $order->voucher? ($subtotal * $order->voucher->sale / 100): 0;
                $shippingFee = $order->shipping ?$order->shipping->shipping_fee: 0;
                // total = subtotal - discount + shipping
                $order->total_price = max($subtotal - $discount + $shippingFee, 0);
                $account = Auth::user();
                if ($user = Auth::user()) {
                    $order->update_by = $user->id;
                }

                //này thêm thông báo cho tài khoản 
                if ($order->isDirty('order_status') && $order->account) {

                    $messageText = match($order->order_status) {
                        1 => "Đơn hàng #{$order->id} đang chờ xử lý",
                        2 => "Đơn hàng #{$order->id} đang được giao tới bạn",
                        3 => "Đơn hàng #{$order->id} đã được giao thành công",
                        4 => "Đơn hàng #{$order->id} đã yêu cầu đổi trả",
                        5 => "Đơn hàng #{$order->id} đã hủy bởi hệ thống",
                        default => "Trạng thái đơn hàng thay đổi",
                    };

                    \App\Models\Notification::create([
                        'account_id' => $order->account->id,
                        'title' => "Cập nhật đơn hàng",
                        'content' => $messageText,
                        'is_important' => $order->order_status == 5, // nếu hủy thì đánh dấu quan trọng
                    ]);
                    Log::info("Notification đã tạo cho Order ID: {$order->id}", [
                        // 'notification_id' => $notification->id,
                        'account_id' => $order->account->id,
                        'status' => $order->status,
                        'message' => $messageText
                        ]);
                }
            });
        }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
    public function updatedBy()
    {
        return $this->belongsTo(Account::class, 'update_by', 'id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_orders')
            ->withPivot('quantity', 'price');
    }
    public function productOrders()
    {
        return $this->hasMany(ProductOrder::class);
    }
    public function voucher()
    {
        return $this->belongsTo(Voucher::class);
    }
    public function shipping()
    {
        return $this->belongsTo(Shipping::class);
    }
}
