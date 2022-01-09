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
        DB::table('admins')->insert([
            'id' => 1,
            'email' => "admin@gmail.com",
            'password' => Hash::make('12345678')
        ]);
        DB::table('users')->insert([
            'id' => 2,
            'phone' => "+905453130300",
            'code' => "112233",
        ]);
    }
}
