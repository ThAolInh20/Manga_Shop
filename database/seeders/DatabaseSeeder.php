<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Tạo dữ liệu từ bảng cha tới con theo thứ tự foreign keys

        // Bảng cha - không phụ thuộc bảng khác
        // $this->call(UserSeeder::class);
        $this->call(AccountSeeder::class);
        $this->call(SupplierSeeder::class);
        $this->call(VoucherSeeder::class);
        $this->call(WebsiteSeeder::class);
        $this->call(WebsiteCustomsSeeder::class);

        // Bảng phụ - phụ thuộc bảng cha
        $this->call(CategorySeeder::class);
        $this->call(ProductSeeder::class);
        $this->call(ShippingSeeder::class);
        $this->call(OrderSeeder::class);

        // Bảng trung gian - phụ thuộc nhiều bảng
        $this->call(ProductSupplierSeeder::class);
        $this->call(ProductOrderSeeder::class);
        $this->call(CartSeeder::class);
        $this->call(CommentSeeder::class);
        $this->call(NotificationSeeder::class);
        $this->call(WishlistSeeder::class);
    }
}
