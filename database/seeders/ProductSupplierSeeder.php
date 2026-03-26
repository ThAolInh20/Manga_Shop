<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('product_suppliers')->insert([
            [
                'product_id' => 1,
                'supplier_id' => 1,
                'import_price' => 50000,
                'quantity' => 100,
                'detail' => 'Nhập hàng từ Nhà cung cấp A',
                'import_by' => 1,
                'created_at' => now()->subMonths(2),
                'updated_at' => now()->subMonths(2),
            ],
            [
                'product_id' => 2,
                'supplier_id' => 2,
                'import_price' => 45000,
                'quantity' => 80,
                'detail' => 'Nhập hàng từ Nhà cung cấp B',
                'import_by' => 1,
                'created_at' => now()->subMonths(1),
                'updated_at' => now()->subMonths(1),
            ],
            [
                'product_id' => 3,
                'supplier_id' => 1,
                'import_price' => 40000,
                'quantity' => 120,
                'detail' => 'Nhập hàng từ Nhà cung cấp A',
                'import_by' => 1,
                'created_at' => now()->subMonths(2),
                'updated_at' => now()->subMonths(2),
            ],
            [
                'product_id' => 4,
                'supplier_id' => 3,
                'import_price' => 55000,
                'quantity' => 60,
                'detail' => 'Nhập hàng từ Nhà cung cấp C',
                'import_by' => 1,
                'created_at' => now()->subMonth(),
                'updated_at' => now()->subMonth(),
            ],
            [
                'product_id' => 5,
                'supplier_id' => 2,
                'import_price' => 60000,
                'quantity' => 50,
                'detail' => 'Nhập hàng từ Nhà cung cấp B',
                'import_by' => 1,
                'created_at' => now()->subMonths(3),
                'updated_at' => now()->subMonths(3),
            ],
        ]);
    }
}
