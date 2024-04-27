<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('role')->insert([
            'id' => 1,
            'role' => 'Admin',
        ]);

        DB::table('role')->insert([
            'id' => 2,
            'role' => 'Petugas',
        ]);
    }
}
