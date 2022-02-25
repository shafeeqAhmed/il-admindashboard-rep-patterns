<?php

namespace Database\Seeders;

use App\Models\BoatService;
use Illuminate\Database\Seeder;

class BoatServiceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        BoatService::factory()
            ->count(50)
            ->create();
    }
}
