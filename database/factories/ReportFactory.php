<?php

namespace Database\Factories;

use App\Models\Fire;
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
            'description' => $this->faker->text(20),
            'den_degree' => $this->faker->randomElement(['High', 'Normal', 'Low']),
            'lat_lang' => json_decode(  '{"lat": "36.236304", "lng": "37.115055"}') ,
            'fire_id' =>  $this->faker->numberBetween(1, 3),
            'created_at' => $this->faker->dateTime,
        ];
    }
}
