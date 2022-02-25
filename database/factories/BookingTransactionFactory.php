<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BookingTransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'booking_transaction_uuid' => $this->faker->uuid(),
            'booking_id' => $this->faker->numberBetween(1,10),
            'price' => $this->faker->numberBetween(60,1000),
            'user_card_id'=>3
        ];
    }
}
