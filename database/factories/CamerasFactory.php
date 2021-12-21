<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CamerasFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'lat_lang' => '[{"lat": "84812", "lang": "8484"}]',
        ];
    }
}
