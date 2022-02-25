<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_cards', function (Blueprint $table) {
            $table->id();
            $table->uuid('card_uuid')->unique();
            $table->string('card_id')->nullable()->comment('scr_id we receive from checkout for card detail');
            $table->foreignId('user_id')->constrained('users');
            $table->string('card_type')->nullable();
            $table->string('card_name')->nullable();
            $table->string('last_digits')->nullable();
            $table->string('expiry')->nullable();
            $table->string('token')->nullable();
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
        Schema::dropIfExists('user_cards');
    }
}
