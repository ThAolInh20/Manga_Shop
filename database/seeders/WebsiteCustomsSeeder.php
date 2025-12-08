<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WebsiteCustomsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('website_customs')->insert([
            'address'           => 'Hà Nội, Việt Nam',
            'hotline'           => '0849838298',
            'email'             => 'mangashop@example.com',
            'primary_color'     => '#ffcc00',
            'background_color'  => '#ffffff',
            // 'background'        => '/images/background.png',
            'font_family'       => 'Inter, sans-serif',
            // 'logo'              => '/images/logo.png',
            // 'banner_main'       => '/images/banner_main.jpg',
            // 'sub_banners'       => json_encode([
            //     '/images/banner1.jpg',
            //     '/images/banner2.jpg',
            //     '/images/banner3.jpg',
            // ]),
            'created_at'        => now(),
            'updated_at'        => now(),
        ]);
    }
}
