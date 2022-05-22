<?php

namespace Database\Seeders;

use App\Models\Camera;
use App\Models\Fire;
use App\Models\Report;
use App\Models\Subscribe;
use App\Models\User;
use Database\Factories\CameraFactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        $this->call(UserSeeder::class);
        $this->call(CameraSeeder::class);
        //Report::factory(10)->create();
        //Fire::factory(3)->create();
        //Subscribe::factory(10)->create();
       // User::factory(10)->create();
    }
}
