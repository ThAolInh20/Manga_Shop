<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShippingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('shippings')->insert([
            [
                'account_id' => 2,
                'name_recipient' => 'Nguyễn Văn A',
                'shipping_fee' => 30000,
                'shipping_address' => 'Quận 1, Huyện Thủy Nguyên, Thành phố Hải Phòng',
                'phone_recipient' => '0123456789',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'account_id' => 2,
                'name_recipient' => 'Trần Thị B',
                'shipping_fee' => 25000,
                'shipping_address' => 'Quận 5, Huyện Bình Chánh, Thành phố Hồ Chí Minh',
                'phone_recipient' => '0987654321',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
