<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReportFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'reporter_type' => User::class,
            'reporter_id' => $this->faker->numberBetween(2, 10),
            'status' => $this->faker->randomElement(['New', 'Confirmed', 'End']),
            'description' => $this->faker->text(20),
            'den_degree' => $this->faker->randomElement(['High', 'Normal', 'Low']),
            'lat_lang' => json_decode(  '{"lat": "36.236304", "lang": "37.115055"}') ,
            'created_at' => $this->faker->dateTime,
        ];
    }
}
