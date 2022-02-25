<?php

namespace Database\Seeders;

use App\Models\BoatPost;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class BoatPostTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        BoatPost::factory()
            ->count(20)
            ->state(new Sequence(
                ['media_type' => 'image'],
                ['media_type' => 'video'],
            ))
            ->create();
    }
}
