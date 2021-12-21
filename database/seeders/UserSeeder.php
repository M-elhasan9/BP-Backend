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
            'name' => "admin",
            'email' => "admin@gmail.com",
            'phone' => "05531777777",
            'is_active' => 1,
            'password' => Hash::make('12345678')
        ]);
        DB::table('users')->insert([
            'id' => 2,
            'name' => "user",
            'email' => "user@gmail.com",
            'phone' => "05531777788",
            'is_active' => 0,
            'password' => Hash::make('12345678')
        ]);
    }
}
