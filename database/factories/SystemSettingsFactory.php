<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SystemSettingsFactory extends Factory
{
    public function definition()
    {
        return [
            'system_setting_uuid' => $this->faker->uuid(),
            'vat' => $this->faker->numberBetween(10, 20),
            'boatek_commission_charges' => $this->faker->numberBetween(11, 30),
            'transaction_charges' => $this->faker->numberBetween(100, 1000),
            'withdraw_scheduled_duration' => $this->faker->numberBetween(1, 7),
        ];
    }
}
