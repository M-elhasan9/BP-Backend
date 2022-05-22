<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CameraSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('cameras')->insert([
            'id' => 1,
            'lat_lang' => '{"lat": "36.310935", "lng": "35.816944"}',
            'description' => 'Antakya ormanı',
            'nn_url' => "http://server.yesilkalacak.com/storage/stream/v1.mp4",
        ]);
        DB::table('cameras')->insert([
            'id' => 2,
            'lat_lang' => '{"lat": "36.920435", "lng": "34.442630"}',
            'description' => 'Mersin ormanı',
            'nn_url' => "http://server.yesilkalacak.com/storage/stream/v5.mp4",
        ]);
        DB::table('cameras')->insert([
            'id' => 3,
            'lat_lang' => '{"lat": "36.860343", "lng": "30.486125"}',
            'description' => 'Antalya ormanı',
            'nn_url' => "http://server.yesilkalacak.com/storage/stream/v6.mp4",
        ]);
        DB::table('cameras')->insert([
            'id' => 4,
            'lat_lang' => '{"lat": "38.253810", "lng": "26.981329"}',
            'description' => 'Izmir ormanı',
            'nn_url' => "http://server.yesilkalacak.com/storage/stream/v11.mp4",
        ]);
    }
}
