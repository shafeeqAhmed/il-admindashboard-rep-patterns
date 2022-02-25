<?php

namespace Database\Seeders;

use App\Models\BoatType;
use Illuminate\Database\Seeder;

class BoatTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        BoatType::factory()
            ->count(40)
            ->create();
    }
}
