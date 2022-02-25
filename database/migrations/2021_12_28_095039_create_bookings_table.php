<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->uuid('booking_uuid')->unique();
            $table->string('booking_short_id');
            $table->foreignId('boat_id')->constrained('boats');
            $table->foreignId('user_id')->constrained('users');
            $table->bigInteger('start_date_time');
            $table->bigInteger('end_date_time');
            $table->enum('status',['pending','confirmed','completed','cancelled','rejected'])->default('pending');
            $table->string('saved_timezone');
            $table->string('local_timezone');
            $table->text('notes')->nullable();
            $table->double('booking_price')->comment('total price of the booking');
            $table->double('payment_received')->comment(' price received after all deductions');
            $table->foreignId('card_id')->constrained('user_cards');
            $table->double('boatek_fee')->nullable();
            $table->double('transaction_charges')->nullable();
            $table->double('tax')->nullable();
            $table->double('discount')->nullable();
            $table->double('discount_type')->nullable();
            $table->foreignId('promo_code_id')->nullable()->nullable()->constrained('promo_codes');
            $table->boolean('is_transferred')->default(false);
            $table->boolean('is_refund')->default(false);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_disputed')->default(false);
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
        Schema::dropIfExists('bookings');
    }
}
