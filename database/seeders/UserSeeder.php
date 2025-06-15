<?php
// database/seeders/UserSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Admin utama
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@kedungbanteng.id',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Staff/Operator
        User::create([
            'name' => 'Staff Desa',
            'email' => 'staff@kedungbanteng.id',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'role' => 'staff',
        ]);

        // User biasa untuk testing
        User::create([
            'name' => 'Warga Desa',
            'email' => 'warga@kedungbanteng.id',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);

        // Generate beberapa user lagi
        User::factory(10)->create();
    }
}
