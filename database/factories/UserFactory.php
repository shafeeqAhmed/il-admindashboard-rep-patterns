<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Testing\Fluent\Concerns\Has;

class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_uuid' => $this->faker->uuid(),
            'first_name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'country_name' => $this->faker->country(),
            'country_code' => $this->faker->currencyCode(),
            'city' => $this->faker->city(),
            'verification_code' => Str::random(10),
            'status' => 'active',
            'profile_pic' => $this->faker->imageUrl(360, 360, 'animals', true, 'dogs', true),
            'password' => Hash::make(123456), // 123456
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */

}
