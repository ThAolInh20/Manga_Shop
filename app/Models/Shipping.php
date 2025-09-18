<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Http;
class Shipping extends Model
{
      protected $fillable = [
        'account_id',
        'name_recipient',
        'shipping_fee',
        'shipping_address',
        'phone_recipient',
    ];
    
    protected function extractProvince($address)
    {
        // Tách các phần bằng dấu phẩy
        $parts = array_map('trim', explode(',', $address));

        // Lấy phần cuối cùng
        $lastPart = end($parts);

        // Loại bỏ tiền tố "Tỉnh", "Thành phố", "TP."
        $lastPart = preg_replace('/^(Tỉnh|Thành phố|TP\.?)\s*/iu', '', $lastPart);

        return $lastPart;
    }
    protected function calculateShippingFee()
    {
        if (!$this->shipping_address) return;

        $shippingAddress = $this->extractProvince($this->shipping_address);
        if (!$shippingAddress) return;

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Token'        => config('services.GHN.token'),
            'ShopId'       => config('services.GHN.shop_id'),
        ])->post("https://online-gateway.ghn.vn/shiip/public-api/v2/shipping-order/fee", [
            "service_id"       => 53320,
            "from_district_id" => config('services.GHN.my_address_id'),
            "to_district_id"   => $shippingAddress->district_id,
            "to_ward_code"     => $shippingAddress->ward_code,
            "weight"           => $this->weight ?? 1000,
            "length"           => 20,
            "width"            => 15,
            "height"           => 10,
        ]);

        if ($response->successful()) {
            $fee = $response->json('data.total');
            $this->shipping_fee = $fee;
            $this->total_price  = max(
                0,
                ($this->subtotal_price - ($this->discount ?? 0)) + $fee
            );
        }
    }
    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    
}
