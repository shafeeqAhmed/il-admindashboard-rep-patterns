<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBankDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_details', function (Blueprint $table) {
            $table->id();
            $table->uuid('bank_detail_uuid')->unique();
            $table->foreignId('user_id')->constrained('users');
            $table->string('account_title')->nullable();
            $table->string('account_name');
            $table->string('account_number')->nullable();
            $table->string('iban_account_number')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('sort_code')->nullable();
            $table->string('billing_address');
            $table->string('post_code');
            $table->enum('location_type',['KSA','UK'])->default('KSA');
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
        Schema::dropIfExists('bank_details');
    }
}
