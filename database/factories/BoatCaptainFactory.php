<?php

namespace Database\Factories;

use App\Models\BoatCaptain;
use Illuminate\Database\Eloquent\Factories\Factory;

class BoatCaptainFactory extends Factory
{

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'captain_uuid'=>$this->faker->uuid(),
            'user_id'=>4,
            'boat_id' => $this->faker->numberBetween(1,1),
        ];
    }
}
