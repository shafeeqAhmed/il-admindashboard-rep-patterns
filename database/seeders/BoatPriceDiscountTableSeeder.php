<?php

namespace Database\Seeders;

use App\Models\BoatPriceDiscount;
use Illuminate\Database\Seeder;

class BoatPriceDiscountTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        BoatPriceDiscount::factory()->count(6)->create();
    }
}
