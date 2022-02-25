<?php

namespace Database\Seeders;

use App\Models\Boat;
use App\Models\BoatType;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class BoatTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Boat::factory()
            ->count(4)
            ->state(new Sequence(
                ['price_unit' => 'hour'],
                ['price_unit' => 'half_hour']
            ))
            ->create();
    }
}
