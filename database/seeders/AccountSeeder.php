<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('accounts')->insert([
            [
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => Hash::make('1234'),
                'role' => 0,
                'address' => '123 Main St',
                'phone' => '0123456789',
                'birth' => '1990-01-01',
                'gender' => 'Male',
                'last_login' => now(),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ctv nam',
                'email' => 'ctv1@gmail.com',
                'password' => Hash::make('1234'),
                'role' => 1,
                'address' => '456 Side St',
                'phone' => '0987654321',
                'birth' => '1995-05-15',
                'gender' => 'Female',
                'last_login' => now(),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
