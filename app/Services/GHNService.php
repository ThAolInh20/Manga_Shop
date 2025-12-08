<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GHNService
{
    protected $token;
    protected $shopId;
    protected $baseUrl;
    protected $myAddressId;

    public function __construct()
    {
        $this->token  = config('services.ghn.token');
        $this->shopId = config('services.ghn.shop_id');
        $this->baseUrl = 'https://online-gateway.ghn.vn/shiip/public-api';
        $this->myAddressId = config('services.ghn.my_address_id');
    }

    public function getProvinces()
    {
        Log::info("GHNService - Gọi API getProvinces");

        $res = Http::withHeaders(['Token' => $this->token])
            ->get("{$this->baseUrl}/master-data/province");

        Log::info("GHNService - Response getProvinces", $res->json() ?? []);

        return $res->json('data') ?? [];
    }

    public function getDistricts($provinceId)
    {
        Log::info("GHNService - Gọi API getDistricts", ['province_id' => $provinceId]);

        $res = Http::withHeaders(['Token' => $this->token])
            ->post("{$this->baseUrl}/master-data/district", ['province_id' => $provinceId]);

        Log::info("GHNService - Response getDistricts", $res->json() ?? []);

        return $res->json('data') ?? [];
    }

    public function getWards($districtId)
    {
        Log::info("GHNService - Gọi API getWards", ['district_id' => $districtId]);

        $res = Http::withHeaders(['Token' => $this->token])
            ->post("{$this->baseUrl}/master-data/ward", ['district_id' => $districtId]);

        Log::info("GHNService - Response getWards", $res->json() ?? []);

        return $res->json('data') ?? [];
    }

    // public function calculateFee($toDistrictId, $toWardCode, $weight = 1000, $length = 20, $width = 15, $height = 10)
    // {
    //     $payload = [
    //         "service_id"       => (int)53320,
    //         "from_district_id" => (int)config('services.ghn.my_address_id'),
    //         "to_district_id"   => (int)$toDistrictId,
    //         "to_ward_code"     => $toWardCode,
    //         "weight"           => (int)$weight,
    //         "length"           =>(int) $length,
    //         "width"            =>(int) $width,
    //         "height"           =>(int) $height,
    //     ];

    //     Log::info("GHNService - Gọi API calculateFee", $payload);

    //     $res = Http::withHeaders([
    //         'Token'  => $this->token,
    //         'ShopId' => $this->shopId,
    //     ])->post("{$this->baseUrl}/v2/shipping-order/fee", $payload);

    //     Log::info("GHNService - Response calculateFee", $res->json() ?? []);

    //     return $res->successful() ? $res->json('data.total') : null;
    // }
    public function calculateFee($toDistrictId, $toWardCode, $weight = 1000, $length = 20, $width = 15, $height = 10)
{
    try {
        // 1. Lấy danh sách dịch vụ khả dụng từ GHN
        $resService = Http::withHeaders([
            'Token'  => $this->token,
            'ShopId' => $this->shopId,
        ])->post("{$this->baseUrl}/v2/shipping-order/available-services", [
            "from_district" => (int) config('services.ghn.my_address_id'),
            "to_district"   => (int) $toDistrictId,
            "shop_id"       => (int) $this->shopId,
        ]);

        $services = $resService->json('data') ?? [];

        if (empty($services)) {
            Log::error("Shipping: GHN không có dịch vụ khả dụng từ {$this->shopId} -> {$toDistrictId}");
            return null;
        }

        // 2. Lấy service_id đầu tiên khả dụng
        $serviceId = $services[0]['service_id'];

        // 3. Tính phí ship
        $resFee = Http::withHeaders([
            'Token'  => $this->token,
            'ShopId' => $this->shopId,
        ])->post("{$this->baseUrl}/v2/shipping-order/fee", [
            "service_id"       => (int) $serviceId,
            "from_district_id" => (int) config('services.ghn.my_address_id'),
            "to_district_id"   => (int) $toDistrictId,
            "to_ward_code"     => (string) $toWardCode,
            "weight"           => (int) $weight,
            "length"           => (int) $length,
            "width"            => (int) $width,
            "height"           => (int) $height,
        ]);

        if ($resFee->successful() && $resFee->json('code') == 200) {
            return $resFee->json('data.total');
        }

        Log::error("Shipping: API GHN không trả về phí ship.", ['response' => $resFee->json()]);
        return null;

    } catch (\Exception $e) {
        Log::error("Shipping: Lỗi tính phí GHN", ['exception' => $e->getMessage()]);
        return null;
    }
}
}
