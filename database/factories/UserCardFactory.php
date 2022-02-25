<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserCardFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'card_uuid' => $this->faker->uuid(),
            'card_id' => Str::random(16),
            'last_digits' => $this->faker->numberBetween(100,999),
        ];
    }
}
