<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Sample data for users without specifying the 'id'
        $users = [
            ['name' => 'Admin', 'email' => 'admin@example.com', 'password' => bcrypt('password')],
            ['name' => 'User One', 'email' => 'user1@example.com', 'password' => bcrypt('password')],
            ['name' => 'User Two', 'email' => 'user2@example.com', 'password' => bcrypt('password')],
            ['name' => 'User Three', 'email' => 'user3@example.com', 'password' => bcrypt('password')],
            ['name' => 'User Four', 'email' => 'user4@example.com', 'password' => bcrypt('password')],
            ['name' => 'User Five', 'email' => 'user5@example.com', 'password' => bcrypt('password')],
            ['name' => 'User Six', 'email' => 'user6@example.com', 'password' => bcrypt('password')],
            ['name' => 'User Seven', 'email' => 'user7@example.com', 'password' => bcrypt('password')],
            ['name' => 'User Eight', 'email' => 'user8@example.com', 'password' => bcrypt('password')],
            ['name' => 'User Nine', 'email' => 'user9@example.com', 'password' => bcrypt('password')],
            ['name' => 'User Ten', 'email' => 'user10@example.com', 'password' => bcrypt('password')],
            ['name' => 'User Eleven', 'email' => 'user11@example.com', 'password' => bcrypt('password')],
            ['name' => 'User Twelve', 'email' => 'user12@example.com', 'password' => bcrypt('password')],
            ['name' => 'User Thirteen', 'email' => 'user13@example.com', 'password' => bcrypt('password')],
            ['name' => 'User Fourteen', 'email' => 'user14@example.com', 'password' => bcrypt('password')],
        ];

        // Insert data into the users table
        DB::table('users')->insert($users);
    }
}
