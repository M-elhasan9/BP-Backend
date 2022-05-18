<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class FireFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'den_degree' => $this->faker->numberBetween(1,100),
            'status' => $this->faker->randomElement([1, 2, 2]),
            'lat_lang' => json_decode(  '{"lat": "36.236304", "lng": "37.115055"}') ,
        ];
    }
}
