<?php

namespace Database\Seeders;

use App\Models\Booking;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class BookingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Booking::factory()
            ->state(new Sequence(
                ['status' => 'pending'],
                ['status' => 'confirmed'],
                ['status' => 'completed'],
                ['status' => 'cancelled'],
                ['status' => 'rejected'],
            ))
            ->count(10)
            ->create();
    }
}
