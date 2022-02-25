<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->uuid('notification_uuid')->unique();
            $table->integer('object_id')->comment('it can be id of booking and reivew we identitfy');
            $table->string('object_type')->comment('booking, review, etc');
            $table->integer('sender_id');
            $table->integer('receiver_id');
            $table->enum('sender_type',['boat','user']);
            $table->enum('receiver_type',['boat','user']);
            $table->text('message')->nullable();
            $table->boolean('is_read')->default(false);
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
        Schema::dropIfExists('notifications');
    }
}
