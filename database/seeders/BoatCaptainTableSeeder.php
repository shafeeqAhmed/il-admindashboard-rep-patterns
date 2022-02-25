<?php

namespace Database\Seeders;

use App\Models\BoatCaptain;
use Illuminate\Database\Seeder;

class BoatCaptainTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        BoatCaptain::factory()->count(4)->create();
    }
}
