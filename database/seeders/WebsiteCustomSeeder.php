<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WebsiteCustomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('website_customs')->insert([
            [
                'address' => '123 Đường Chính, Hà Nội',
                'hotline' => '1900-1234',
                'email' => 'support@mangashop.com',
                'primary_color' => '#FF6600',
                'background_color' => '#FFFFFF',
                'background' => 'background.jpg',
                'font_family' => 'Arial, sans-serif',
                'logo' => 'logo.png',
                'banner_main' => 'banner_main.jpg',
                'sub_banners' => 'banner1.jpg,banner2.jpg,banner3.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
