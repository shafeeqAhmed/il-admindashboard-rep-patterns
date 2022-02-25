<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePromoCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promo_codes', function (Blueprint $table) {
            $table->id();
            $table->uuid('code_uuid');
           $table->foreignId('boat_id')->constrained('boats');
            $table->string('coupon_code');
            $table->bigInteger('valid_from');
            $table->bigInteger('valid_to');
            $table->enum('discount_type',['percentage', 'amount'])->default('percentage');
            $table->double('coupon_amount');
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
        Schema::dropIfExists('promo_codes');
    }
}
