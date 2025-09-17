<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;


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
    protected static function booted()
        {
            static::saving(function ($order) {
                $subtotal = $order->subtotal_price ?? 0;
                $discount = $order->voucher? ($subtotal * $order->voucher->sale / 100): 0;
                $shippingFee = $order->shipping ?$order->shipping->shipping_fee: 0;
                // total = subtotal - discount + shipping
                $order->total_price = max($subtotal - $discount + $shippingFee, 0);
                $accout = Auth::user();
                $order->update_by=$accout->id;
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
