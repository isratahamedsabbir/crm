<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('types')->insert([
            [
                'name' => 'call',
                'slug' => 'call',
                'status' => 'active',
            ],
            [
                'name' => 'email',
                'slug' => 'email',
                'status' => 'active',
            ],
            [
                'name' => 'meeting',
                'slug' => 'meeting',
                'status' => 'active',
            ]
        ]);
    }
}
