<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubscribeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id'=>$this->faker->numberBetween(2,10),
            'description'=>$this->faker->text(20),
            'lat_lang' => json_decode('{"lat": "36.235630", "lng": "37.115824"}'),
        ];
    }
}
