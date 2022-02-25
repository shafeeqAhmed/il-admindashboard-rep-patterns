<?php

namespace Database\Seeders;

use App\Models\BookingTransaction;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class BookingTransactionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        BookingTransaction::factory()
            ->count(14)
            ->state(new Sequence(
                ['transaction_status' => 'pending'],
                ['transaction_status' => 'confirmed'],
                ['transaction_status' => 'rejected'],
                ['transaction_status' => 'cancelled'],
                ['transaction_status' => 'authorized'],
                ['transaction_status' => 'refunded'],
                ['transaction_status' => 'completed'],
            ))
            ->create();
    }
}
