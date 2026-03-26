<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('carts')->insert([
            [
                'account_id' => 2,
                'product_id' => 1,
                'quantity' => 2,
                'price' => 80000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'account_id' => 2,
                'product_id' => 3,
                'quantity' => 1,
                'price' => 70000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'account_id' => 2,
                'product_id' => 5,
                'quantity' => 1,
                'price' => 85000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
