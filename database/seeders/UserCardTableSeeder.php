<?php

namespace Database\Seeders;

use App\Models\UserCard;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class UserCardTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        UserCard::factory()
            ->count(4)
            ->state(new Sequence(
                ['user_id' => 1],
                ['user_id' => 2],
                ['user_id' => 3],
                ['user_id' => 4],
            ))
            ->create();
    }
}
