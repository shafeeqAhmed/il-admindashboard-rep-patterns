<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWithdrawBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('withdraw_bookings', function (Blueprint $table) {
            $table->id();
            $table->uuid('withdraw_booking_uuid')->unique();
            $table->foreignId('booking_id')->constrained('bookings');
            $table->foreignId('withdraw_id')->constrained('withdraws');


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('withdraw_bookings');
    }
}
