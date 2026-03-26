<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('comments')->insert([
            [
                'detail' => 'Sản phẩm rất tốt, hình ảnh sắc nét, giá cả hợp lý',
                'rate' => 5,
                'account_id' => 2,
                'product_id' => 1,
                'created_at' => now()->subDays(4),
                'updated_at' => now()->subDays(4),
            ],
            [
                'detail' => 'Manga này thực sự hay, người dịch rất tốt',
                'rate' => 5,
                'account_id' => 2,
                'product_id' => 2,
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(2),
            ],
            [
                'detail' => 'Bản in chất lượng tốt nhưng giá hơi cao',
                'rate' => 4,
                'account_id' => 2,
                'product_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'detail' => 'Tiểu thuyết đặc sắc với tích cốt phức tạp',
                'rate' => 4,
                'account_id' => 2,
                'product_id' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
