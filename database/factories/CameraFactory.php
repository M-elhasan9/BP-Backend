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
            'lat_lang' => '{"lat": "84812", "lang": "8484"}',
            'description' => $this->faker->text(20),
            'url' => 'https://www.youtube.com/watch?v=pk4mCPYVe5o&ab_channel=AbdAlrazakHAJSAAED',
            'nn_url' => 'https://www.youtube.com/watch?v=pk4mCPYVe5o&ab_channel=AbdAlrazakHAJSAAED',
        ];
    }
}
