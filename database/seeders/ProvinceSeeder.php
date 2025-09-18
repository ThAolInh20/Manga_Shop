<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProvinceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $provinces = [
            ['province_id' => 201, 'name' => 'Hà Nội'],
            ['province_id' => 202, 'name' => 'Hồ Chí Minh'],
            ['province_id' => 203, 'name' => 'Hải Phòng'],
            ['province_id' => 204, 'name' => 'Đà Nẵng'],
            ['province_id' => 205, 'name' => 'Cần Thơ'],
            ['province_id' => 206, 'name' => 'An Giang'],
            ['province_id' => 207, 'name' => 'Bà Rịa - Vũng Tàu'],
            ['province_id' => 208, 'name' => 'Bắc Giang'],
            ['province_id' => 209, 'name' => 'Bắc Kạn'],
            ['province_id' => 210, 'name' => 'Bạc Liêu'],
            ['province_id' => 211, 'name' => 'Bắc Ninh'],
            ['province_id' => 212, 'name' => 'Bến Tre'],
            ['province_id' => 213, 'name' => 'Bình Định'],
            ['province_id' => 214, 'name' => 'Bình Dương'],
            ['province_id' => 215, 'name' => 'Bình Phước'],
            ['province_id' => 216, 'name' => 'Bình Thuận'],
            ['province_id' => 217, 'name' => 'Cà Mau'],
            ['province_id' => 218, 'name' => 'Cao Bằng'],
            ['province_id' => 219, 'name' => 'Đắk Lắk'],
            ['province_id' => 220, 'name' => 'Đắk Nông'],
            ['province_id' => 221, 'name' => 'Điện Biên'],
            ['province_id' => 222, 'name' => 'Đồng Nai'],
            ['province_id' => 223, 'name' => 'Đồng Tháp'],
            ['province_id' => 224, 'name' => 'Gia Lai'],
            ['province_id' => 225, 'name' => 'Hà Giang'],
            ['province_id' => 226, 'name' => 'Hà Nam'],
            ['province_id' => 227, 'name' => 'Hà Tĩnh'],
            ['province_id' => 228, 'name' => 'Hải Dương'],
            ['province_id' => 229, 'name' => 'Hậu Giang'],
            ['province_id' => 230, 'name' => 'Hòa Bình'],
            ['province_id' => 231, 'name' => 'Hưng Yên'],
            ['province_id' => 232, 'name' => 'Khánh Hòa'],
            ['province_id' => 233, 'name' => 'Kiên Giang'],
            ['province_id' => 234, 'name' => 'Kon Tum'],
            ['province_id' => 235, 'name' => 'Lai Châu'],
            ['province_id' => 236, 'name' => 'Lâm Đồng'],
            ['province_id' => 237, 'name' => 'Lạng Sơn'],
            ['province_id' => 238, 'name' => 'Lào Cai'],
            ['province_id' => 239, 'name' => 'Long An'],
            ['province_id' => 240, 'name' => 'Nam Định'],
            ['province_id' => 241, 'name' => 'Nghệ An'],
            ['province_id' => 242, 'name' => 'Ninh Bình'],
            ['province_id' => 243, 'name' => 'Ninh Thuận'],
            ['province_id' => 244, 'name' => 'Phú Thọ'],
            ['province_id' => 245, 'name' => 'Phú Yên'],
            ['province_id' => 246, 'name' => 'Quảng Bình'],
            ['province_id' => 247, 'name' => 'Quảng Nam'],
            ['province_id' => 248, 'name' => 'Quảng Ngãi'],
            ['province_id' => 249, 'name' => 'Quảng Ninh'],
            ['province_id' => 250, 'name' => 'Quảng Trị'],
            ['province_id' => 251, 'name' => 'Sóc Trăng'],
            ['province_id' => 252, 'name' => 'Sơn La'],
            ['province_id' => 253, 'name' => 'Tây Ninh'],
            ['province_id' => 254, 'name' => 'Thái Bình'],
            ['province_id' => 255, 'name' => 'Thái Nguyên'],
            ['province_id' => 256, 'name' => 'Thanh Hóa'],
            ['province_id' => 257, 'name' => 'Thừa Thiên Huế'],
            ['province_id' => 258, 'name' => 'Tiền Giang'],
            ['province_id' => 259, 'name' => 'Trà Vinh'],
            ['province_id' => 260, 'name' => 'Tuyên Quang'],
            ['province_id' => 261, 'name' => 'Vĩnh Long'],
            ['province_id' => 262, 'name' => 'Vĩnh Phúc'],
            ['province_id' => 263, 'name' => 'Yên Bái'],
        ];

        DB::table('provinces')->insert($provinces);
    }
}
