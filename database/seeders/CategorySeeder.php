<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categories')->insert([
            [
                'name' => 'VIP',
                'slug' => 'vip',
                'status' => 'active',
            ],
            [
                'name' => 'Business',
                'slug' => 'business',
                'status' => 'active',
            ],
            [
                'name' => 'Personal',
                'slug' => 'personal',
                'status' => 'active',
            ]
        ]);
    }
}
