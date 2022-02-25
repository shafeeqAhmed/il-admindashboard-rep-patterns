<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BoatServiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'boat_service_uuid'=>$this->faker->uuid(),
            'boat_id'=>$this->faker->numberBetween(1,4),
            'name' => $this->faker->name
        ];
    }
}
