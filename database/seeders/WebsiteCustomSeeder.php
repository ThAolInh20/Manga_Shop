<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\WebsiteCustom; // nhớ import model

class WebsiteCustomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        WebsiteCustom::create([
            'address'          => 'Hà Nội',
            'hotline'          => '0123-456-789',
            'email'            => 'support@mangashop.com',
            'primary_color'    => '#ff6600',
            'background_color' => '#ffffff',
            'background'       => 'default-bg.jpg',
            'font_family'      => 'Roboto, sans-serif',
           
            
        ]);
    }
}
