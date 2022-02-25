<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBoatsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('boats', function (Blueprint $table) {
            $table->id();
            $table->uuid('boat_uuid')->unique();
            $table->string('name')->nullable();
            $table->string('number')->nullable();
            $table->string('manufacturer')->nullable();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('boat_type_id')->constrained('boat_types');

            $table->enum('onboard_name',['boat_type','1__boat_detail','2__add_services','3__add_location','4__add_captain','5__add_price'])->default('boat_type');

            $table->text('profile_pic')->nullable();
            $table->integer('capacity')->nullable();
            $table->longText('info')->nullable();
            $table->string('location')->nullable();
            $table->double('lat')->nullable();
            $table->double('lng')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->double('price')->default(0);
            $table->enum('price_unit', ['hour', 'half_hour'])->nullable();
            $table->enum('status', ['active', 'deactive'])->default('active');
            $table->boolean('is_approved')->default(true);
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
        Schema::dropIfExists('boats');
    }

}
