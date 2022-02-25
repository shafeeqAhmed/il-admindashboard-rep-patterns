<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BoatStoriesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'story_uuid'=>$this->faker->uuid(),
            'boat_id'=>$this->faker->numberBetween(1,4),
            'text' => $this->faker->text(),
            'story_image' => $this->faker->imageUrl(360, 360, 'animals', true, 'dogs', true),
            'story_video' => '',
            'video_thumbnail' => '',
        ];
    }
}
