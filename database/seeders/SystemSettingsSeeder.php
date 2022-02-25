<?php

namespace Database\Seeders;

use App\Models\SystemSettings;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class SystemSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SystemSettings::factory()
            ->count(5)
            ->state(new Sequence(
                ['is_active' => 0],
                ['is_active' => 0],
                ['is_active' => 0],
                ['is_active' => 0],
                ['is_active' => 1],
            ))
            ->create();
    }
}
