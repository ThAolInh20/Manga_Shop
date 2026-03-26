<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('suppliers')->insert([
            [
                'name' => 'Nhà cung cấp A',
                'address' => '123 Đường ABC, Hà Nội',
                'phone' => '0123456789',
                'email' => 'supplier1@example.com',
                'tax_code' => 'TAX001',
                'contract' => 'Hợp đồng 1',
                'is_active' => true,
                'link_contract' => 'https://example.com/contract1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Nhà cung cấp B',
                'address' => '456 Đường XYZ, TP.HCM',
                'phone' => '0987654321',
                'email' => 'supplier2@example.com',
                'tax_code' => 'TAX002',
                'contract' => 'Hợp đồng 2',
                'is_active' => true,
                'link_contract' => 'https://example.com/contract2',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Nhà cung cấp C',
                'address' => '789 Đường DEF, Đà Nẵng',
                'phone' => '0912345678',
                'email' => 'supplier3@example.com',
                'tax_code' => 'TAX003',
                'contract' => 'Hợp đồng 3',
                'is_active' => true,
                'link_contract' => 'https://example.com/contract3',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
