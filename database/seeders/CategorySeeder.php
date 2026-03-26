<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categories')->insert([
            [
                'name' => 'Manga Nhật Bản',
                'detail' => 'Các bộ truyện tranh Nhật Bản (Manga)',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Truyện tranh Hàn Quốc',
                'detail' => 'Các bộ truyện tranh Hàn Quốc (Manhwa)',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Truyện tranh Trung Quốc',
                'detail' => 'Các bộ truyện tranh Trung Quốc (Manhua)',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Light Novel',
                'detail' => 'Các bộ tiểu thuyết nhẹ (Light Novel)',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Sách khác',
                'detail' => 'Các sản phẩm khác',
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
