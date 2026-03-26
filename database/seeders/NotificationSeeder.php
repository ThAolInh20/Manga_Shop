<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('notifications')->insert([
            [
                'account_id' => 2,
                'title' => 'Đơn hàng đã được xác nhận',
                'content' => 'Đơn hàng #1 của bạn đã được xác nhận và sẽ sớm được vận chuyển',
                'is_read' => false,
                'is_important' => false,
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(5),
            ],
            [
                'account_id' => 2,
                'title' => 'Đơn hàng đang được vận chuyển',
                'content' => 'Đơn hàng #1 của bạn đang được vận chuyển. Mã vận đơn: GHN123456',
                'is_read' => false,
                'is_important' => false,
                'created_at' => now()->subDays(3),
                'updated_at' => now()->subDays(3),
            ],
            [
                'account_id' => 2,
                'title' => 'Chương trình khuyến mãi mới',
                'content' => 'Khuyến mãi mùa hè lên tới 30% cho các sản phẩm hot',
                'is_read' => true,
                'is_important' => true,
                'created_at' => now()->subDay(),
                'updated_at' => now()->subDay(),
            ],
            [
                'account_id' => 2,
                'title' => 'Cảnh báo: Sản phẩm yêu thích sắp hết hàng',
                'content' => 'Sản phẩm "One Piece Volume 1" mà bạn yêu thích sắp hết hàng',
                'is_read' => false,
                'is_important' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
