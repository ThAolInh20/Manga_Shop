<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('orders')->insert([
            [
                'account_id' => 2,
                'order_date' => now()->subDays(5),
                'deliver_date' => now()->addDays(2),
                'order_status' => 1,
                'total_price' => 240000,
                'payment_status' => 1,
                'voucher_id' => 1,
                'subtotal_price' => 200000,
                'shipping_id' => 1,
                'update_by' => 1,
                'created_at' => now()->subDays(5),
                'updated_at' => now(),
            ],
            [
                'account_id' => 2,
                'order_date' => now()->subDays(3),
                'deliver_date' => now()->addDays(4),
                'order_status' => 2,
                'total_price' => 320000,
                'payment_status' => 1,
                'voucher_id' => 2,
                'subtotal_price' => 300000,
                'shipping_id' => 2,
                'update_by' => 1,
                'created_at' => now()->subDays(3),
                'updated_at' => now(),
            ],
            [
                'account_id' => 2,
                'order_date' => now()->subDay(),
                'deliver_date' => now()->addDays(5),
                'order_status' => 0,
                'total_price' => 180000,
                'payment_status' => 0,
                'voucher_id' => null,
                'subtotal_price' => 160000,
                'shipping_id' => 1,
                'update_by' => 1,
                'created_at' => now()->subDay(),
                'updated_at' => now(),
            ],
        ]);
    }
}
