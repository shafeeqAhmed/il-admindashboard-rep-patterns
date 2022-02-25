<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBoatPriceDiscountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('boat_price_discounts', function (Blueprint $table) {
            $table->id();
            $table->uuid('discount_uuid');
            $table->float('discount_after');
            $table->integer('percentage');
            $table->foreignId('boat_id')->constrained('boats');
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
        Schema::dropIfExists('boat_price_discounts');
    }
}
