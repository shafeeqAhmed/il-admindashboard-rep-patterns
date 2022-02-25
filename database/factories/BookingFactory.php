<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class BookingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
    return [
            'booking_uuid'=>$this->faker->uuid(),
            'booking_short_id'=>Str::random(8),
            'boat_id'=>$this->faker->numberBetween(1,4),
            'user_id'=>3,
            'start_date_time'=>strtotime("now"),
            'end_date_time'=>strtotime("+1 day"),
            'saved_timezone'=>'UTC',
            'local_timezone'=>'UTC',
            'notes'=>$this->faker->text(),
            'booking_price'=>$this->faker->randomFloat('2','10','100'),
            'payment_received'=>$this->faker->randomFloat('2','10','100'),
            'card_id'=>3,
        ];
    }
}
