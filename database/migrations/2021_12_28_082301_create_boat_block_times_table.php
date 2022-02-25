<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBoatBlockTimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('boat_block_times', function (Blueprint $table) {
            $table->id();
            $table->uuid('blocked_time_uuid');
            $table->foreignId('boat_id')->constrained('boats');
            $table->bigInteger('start_date_time');
            $table->bigInteger('end_date_time');
            $table->string('saved_timezone');
            $table->string('local_timezone');
            $table->text('notes')->nullable();
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
        Schema::dropIfExists('boat_block_times');
    }
}
