<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $fillable = [
        'account_id', 'order_date', 'deliver_date', 
        'order_status', 'shipping_fee', 'shipping_address', 
        'total_price', 'payment_status','name_recipient','phone_recipient', 'voucher_id','subtotal_price'
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


    public function account()
    {
        return $this->belongsTo(Account::class);
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
