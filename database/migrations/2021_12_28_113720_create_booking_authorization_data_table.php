<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingAuthorizationDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking_authorization_data', function (Blueprint $table) {
            $table->id();
            $table->uuid('booking_auth_uuid')->unique();
            $table->string('fort_id');
            $table->uuid('merchant_reference');
            $table->double('amount');
            $table->string('card_name')->nullable();
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
        Schema::dropIfExists('booking_authorization_data');
    }
}
