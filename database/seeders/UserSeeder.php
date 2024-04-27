<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'id' => 1,
            'name' => 'Reiz',
            'email' => 'reizcorps@gmail.com',
            'password' => Hash::make('reiz1234'),
            'role' => 'Admin',
        ]);

        DB::table('users')->insert([
            'id' => 2,
            'name' => 'Reiz2',
            'email' => 'velzuwu@gmail.com',
            'password' => Hash::make('reiz1234'),
            'role' => 'Petugas',
        ]);

        DB::table('users')->insert([
            'id' => 3,
            'name' => 'Reiz3',
            'email' => 'reiz@gmail.com',
            'password' => Hash::make('reiz1234'),
            'role' => 'Member',
        ]);
    }
}
