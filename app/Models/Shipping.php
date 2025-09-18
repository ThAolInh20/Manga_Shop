<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Http;
use App\Services\GHNService;
use Illuminate\Support\Facades\Log;

class Shipping extends Model
{
      protected $fillable = [
        'account_id',
        'name_recipient',
        'shipping_fee',
        'shipping_address',
        'phone_recipient',
    ];
    
     protected static function booted()
    {
        static::saving(function ($shipping) {
            $shipping->calculateShippingFee();
        });
    }
    
    //Hàm này trả về địa chỉ theo id
   protected function extractAddressParts($address)
    {
        if (!$address) return null;

        // Tách các phần theo dấu phẩy
        $parts = array_map('trim', explode(',', $address));

        if (count($parts) < 3) return null;

        // Lấy lần lượt từ cuối lên
        $provinceRaw = array_pop($parts);
        $districtRaw = array_pop($parts);
        $wardRaw     = array_pop($parts);

        // Chuẩn hoá tên
        $province = preg_replace('/^(Tỉnh|Thành phố|TP\.?)\s*/iu', '', $provinceRaw);
        $district = preg_replace('/^(Quận|Huyện|Thị xã|TP\.?)\s*/iu', '', $districtRaw);
        $ward     = preg_replace('/^(Xã|Phường|Thị trấn)\s*/iu', '', $wardRaw);

        return [
            'province' => $province,
            'district' => $district,
            'ward'     => $ward,
        ];
    }
    protected function calculateShippingFee()
{
    if (!$this->shipping_address) {
        Log::warning("Shipping: Không có địa chỉ để tính phí ship.");
        return;
    }

    $ghn = app(\App\Services\GHNService::class);

    // Lấy ward, district, province từ địa chỉ
    $parts = $this->extractAddressParts($this->shipping_address);
    Log::info("Shipping: Địa chỉ sau khi tách", $parts ?? []);

    if (!$parts) {
 
        return;
    }

    try {
        // Tìm province
        $provinces = $ghn->getProvinces();
        $province = collect($provinces)
            ->first(fn($item) => stripos($item['ProvinceName'], $parts['province']) !== false);

        if (!$province) {
           
            return;
        }
       

        // Tìm district
        $districts = $ghn->getDistricts($province['ProvinceID']);
        $district = collect($districts)
            ->first(fn($item) => stripos($item['DistrictName'], $parts['district']) !== false);

        if (!$district) {
           
            return;
        }
        Log::info("Shipping: District match", $district);

        // Tìm ward
        $wards = $ghn->getWards($district['DistrictID']);
        $ward = collect($wards)
            ->first(fn($item) => stripos($item['WardName'], $parts['ward']) !== false);

        if (!$ward) {
            Log::error("Shipping: Không tìm thấy xã/phường trong GHN", [
                'ward_input' => $parts['ward'],
                'all_wards'  => $wards,
            ]);
            return;
        }
        Log::info("Shipping: Ward match", $ward);

        // Tính phí ship
        $fee = $ghn->calculateFee(
            $district['DistrictID'],
            $ward['WardCode'],
            $this->weight ?? 1000,
            20,
            15,
            10
        );

        if ($fee) {
            $this->shipping_fee = $fee;
            Log::info("Shipping: Tính phí ship thành công", ['fee' => $fee]);
        } else {
            Log::error("Shipping: API GHN không trả về phí ship.");
        }
    } catch (\Exception $e) {
        Log::error("Shipping: Lỗi khi gọi GHN API", [
            'message' => $e->getMessage(),
            'trace'   => $e->getTraceAsString(),
        ]);
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
