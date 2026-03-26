<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WebsiteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('website')->insert([
            [
                'address' => '123 Đường Chính, Hà Nội',
                'hotline' => '1900-1234',
                'email' => 'contact@mangashop.com',
                'buy_guide' => 'Hướng dẫn mua hàng online',
                'return_policy' => 'Chính sách hoàn trả 30 ngày',
                'private_policy' => 'Chính sách bảo mật dữ liệu',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
