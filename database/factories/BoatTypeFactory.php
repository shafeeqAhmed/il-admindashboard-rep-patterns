<?php

namespace Database\Factories;

use App\Models\BoatType;
use Illuminate\Database\Eloquent\Factories\Factory;

class BoatTypeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = BoatType::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'boat_type_uuid' => $this->faker->uuid(),
            'name' => $this->faker->name(),
            'pic' => $this->faker->imageUrl(360, 360, 'animals', true, 'dogs', true),

        ];
    }
}
