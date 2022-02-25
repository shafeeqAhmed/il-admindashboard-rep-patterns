<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingTransactionsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('booking_transactions', function (Blueprint $table) {
            $table->id();
            $table->uuid('booking_transaction_uuid')->unique();
            $table->foreignId('booking_id')->constrained('bookings');
            $table->double('price');
            $table->double('boat_earning')->default(0);
            $table->double('customer_refund')->default(0);
            $table->string('gateway_response')->nullable();
            $table->string('request_parameters')->nullable();
            $table->enum('transaction_status', ['pending', 'confirmed', 'rejected', 'cancelled', 'authorized', 'refunded', 'completed'])->default('pending');
            $table->foreignId('user_card_id')->constrained('user_cards');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('booking_transactions');
    }

}
