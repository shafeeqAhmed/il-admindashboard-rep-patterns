<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRescheduledBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rescheduled_bookings', function (Blueprint $table) {
            $table->id();
            $table->uuid('rescheduled_booking_uuid')->unique();
            $table->foreignId('booking_id')->constrained('bookings');
            $table->integer('rescheduled_by_id');
            $table->enum('rescheduled_by_type',['boat', 'user', 'admin']);
            $table->bigInteger('previous_from_time');
            $table->bigInteger('previous_to_time');
            $table->enum('previous_status',['pending','confirmed','completed','cancelled','rejected']);
            $table->boolean('is_active')->default(true);
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
        Schema::dropIfExists('rescheduled_bookings');
    }
}
