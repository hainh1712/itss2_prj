<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'id' => 1,
                'name' => 'Admin',
                'password' => Hash::make('admin'),
                'email' => 'admin@gmail.com',
                'role' => 1,
                'created_at' => now(),
                'updated_at' =>now(),
                'email_verified_at' => now()
            ],
            [
                'id' => 2,
                'name' => 'User',
                'password' => Hash::make('user'),
                'email' => 'user@gmail.com',
                'role' => 2,
                'created_at' => now(),
                'updated_at' =>now(),
                'email_verified_at' => now()
            ]
        ]);
    }
}
