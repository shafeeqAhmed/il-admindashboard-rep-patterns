<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BoatPriceDiscountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'discount_uuid'=>$this->faker->uuid(),
            'discount_after' => $this->faker->numberBetween(1,10),
            'percentage' => $this->faker->numberBetween(5,50),
            'boat_id'=>$this->faker->numberBetween(1,3),
        ];
    }
}
