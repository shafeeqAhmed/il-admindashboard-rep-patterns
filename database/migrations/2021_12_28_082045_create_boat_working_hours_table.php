<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBoatWorkingHoursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('boat_working_hours', function (Blueprint $table) {
            $table->id();
            $table->uuid('working_hour_uuid')->unique();
            $table->foreignId('boat_id')->constrained('boats');
            $table->string('day');
            $table->time('from_time');
            $table->time('to_time');
            $table->string('saved_timezone');
            $table->string('local_timezone');
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
        Schema::dropIfExists('boat_working_hours');
    }
}
