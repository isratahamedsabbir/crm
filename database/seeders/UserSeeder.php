<?php

namespace Database\Seeders;

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
        DB::table('users')->insertOrIgnore([
            [
                'name' => 'Admin',
                'email' => 'admin@admin.com',
                'password' => Hash::make('12345678'),
                'email_verified_at' => now(),
                'role' => 'admin',
                'status' => 'active',
                'email_verified_at' => now()
            ],
            [
                'name' => 'User',
                'email' => 'user@user.com',
                'password' => Hash::make('12345678'),
                'email_verified_at' => now(),
                'role' => 'user',
                'status' => 'active',
                'email_verified_at' => now()
            ],
        ]);
    }
}
