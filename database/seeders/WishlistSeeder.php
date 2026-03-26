<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WishlistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('wishlists')->insert([
            [
                'account_id' => 2,
                'product_id' => 1,
                'created_at' => now()->subDays(10),
                'updated_at' => now()->subDays(10),
            ],
            [
                'account_id' => 2,
                'product_id' => 3,
                'created_at' => now()->subDays(7),
                'updated_at' => now()->subDays(7),
            ],
            [
                'account_id' => 2,
                'product_id' => 4,
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(5),
            ],
            [
                'account_id' => 2,
                'product_id' => 5,
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(2),
            ],
        ]);
    }
}
