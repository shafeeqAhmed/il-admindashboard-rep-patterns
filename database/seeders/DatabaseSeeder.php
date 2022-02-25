<?php

namespace Database\Seeders;

use App\Models\UserCard;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run() {
        $this->call([
            BoatTypeSeeder::class,
            UserTableSeeder::class,
            BoatTableSeeder::class,
            BoatPostTableSeeder::class,
            BoatStoriesTableSeeder::class,
            BoatCaptainTableSeeder::class,
            UserCardTableSeeder::class,
            BookingTableSeeder::class,
            BookingTransactionTableSeeder::class,
            BoatServiceTableSeeder::class,
            BoatPriceDiscountTableSeeder::class,
            BoatDefaultServicesTableSeeder::class,
        ]);
    }

}

