<?php

namespace Database\Seeders;

use App\Models\BoatStories;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class BoatStoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        BoatStories::factory()
            ->count(50)
            ->create();
    }
}
