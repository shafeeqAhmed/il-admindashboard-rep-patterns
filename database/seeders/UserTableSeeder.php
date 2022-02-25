<?php

namespace Database\Seeders;

use App\Models\Boat;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()
            ->count(4)
            ->state(new Sequence(
                ['role' => 'admin'],
                ['role' => 'boat_owner'],
                ['role' => 'customer'],
                ['role' => 'captain'],
            ))
            ->create();
    }
}
