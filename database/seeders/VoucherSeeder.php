<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VoucherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('vouchers')->insert([
            [
                'code' => 'SAVE10',
                'sale' => 10,
                'is_active' => true,
                'date_end' => now()->addMonths(1),
                'max_discount' => 100000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'SAVE20',
                'sale' => 20,
                'is_active' => true,
                'date_end' => now()->addMonths(2),
                'max_discount' => 200000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'SUMMER30',
                'sale' => 30,
                'is_active' => true,
                'date_end' => now()->addMonths(3),
                'max_discount' => 300000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
