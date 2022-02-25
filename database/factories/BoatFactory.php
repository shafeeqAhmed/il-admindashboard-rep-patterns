<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class BoatFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'boat_uuid' => $this->faker->uuid(),
            'name' => $this->faker->name().'-'.Str::random(4),
            'number' => $this->faker->name().'-'.Str::random(4),
            'manufacturer' => $this->faker->name(),

            'onboard_name' => '5__add_price',
            'profile_pic' => $this->faker->imageUrl(360, 360, 'animals', true, 'dogs', true),
            'capacity'=>$this->faker->numberBetween(1,50),
            'user_id'=>2,
            'boat_type_id'=>$this->faker->numberBetween(1,4),
            'info' => $this->faker->text(),
            'location' => $this->faker->address(),
            'lat'=> $this->faker->latitude(),
            'lng'=> $this->faker->longitude(),
            'state'=> $this->faker->name(),
            'country'=> $this->faker->country(),
            'city'=> $this->faker->city(),
            'price'=> $this->faker->randomFloat('2','2','1000'),

            ];
    }
}
