<?php

namespace Database\Seeders;

use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class BoatDefaultServicesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        BoatDefaultService::factory()
            ->count(20)
            ->state(new Sequence(
                ['name' => 'Free Wifi'],
                ['name' => 'Power Bank'],
                ['name' => 'Free Towels'],
                ['name' => 'Free Meals'],
                ['name' => 'Free Drink'],
                ['name' => 'Free Snacks'],
            ))->state(new Sequence(
                ['boat_default_services' => '163e6894-cf6f-3736-ac31-0ef5cb5a1231'],
                ['boat_default_services' => '163e6894-cf6f-3736-ac31-0ef5cb5a1232'],
                ['boat_default_services' => '163e6894-cf6f-3736-ac31-0ef5cb5a1233'],
                ['boat_default_services' => '163e6894-cf6f-3736-ac31-0ef5cb5a1234'],
                ['boat_default_services' => '163e6894-cf6f-3736-ac31-0ef5cb5a1235'],
                ['boat_default_services' => '163e6894-cf6f-3736-ac31-0ef5cb5a1236'],

            ))
            ->create();
    }
}
