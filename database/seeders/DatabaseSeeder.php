<?php

namespace Database\Seeders;

use App\Models\Cameras;
use App\Models\Reports;
use App\Models\Subscribes;
use App\Models\User;
use Database\Factories\CamerasFactory;
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
        Cameras::factory(10)->create();
        Reports::factory(10)->create();
        Subscribes::factory(10)->create();
        User::factory(10)->create();
    }
}
