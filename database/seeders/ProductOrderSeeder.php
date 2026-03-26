<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('product_orders')->insert([
            [
                'product_id' => 1,
                'order_id' => 1,
                'quantity' => 2,
                'price' => 80000,
            ],
            [
                'product_id' => 2,
                'order_id' => 1,
                'quantity' => 1,
                'price' => 67500,
            ],
            [
                'product_id' => 3,
                'order_id' => 1,
                'quantity' => 1,
                'price' => 66500,
            ],
            [
                'product_id' => 4,
                'order_id' => 2,
                'quantity' => 2,
                'price' => 85000,
            ],
            [
                'product_id' => 5,
                'order_id' => 2,
                'quantity' => 1,
                'price' => 80750,
            ],
            [
                'product_id' => 1,
                'order_id' => 3,
                'quantity' => 2,
                'price' => 80000,
            ],
        ]);
    }
}
