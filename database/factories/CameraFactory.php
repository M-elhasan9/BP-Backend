<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CameraFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'lat_lang' => json_decode('{"lat": "36.236304", "lng": "37.115055"}'),
            'description' => $this->faker->text(20),
            'url' => 'https://www.youtube.com/watch?v=pk4mCPYVe5o&ab_channel=AbdAlrazakHAJSAAED',
        ];
    }
}
