<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BoatPostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'post_uuid'=>$this->faker->uuid(),
            'boat_id'=>$this->faker->numberBetween(1,4),
            'caption' => $this->faker->text(),
            'text' => $this->faker->text(),
            'src' => $this->faker->imageUrl(360, 360, 'animals', true, 'dogs', true)
        ];
    }
}
