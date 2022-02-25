<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBoatPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('boat_posts', function (Blueprint $table) {
            $table->id();
            $table->uuid('post_uuid')->unique();
            $table->foreignId('boat_id')->constrained('boats');
            $table->text('caption')->nullable();
            $table->text('text')->nullable();
            $table->enum('media_type',['image', 'video']);
            $table->longText('src');
            $table->enum('status',['approved','rejected'])->default('approved');
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
        Schema::dropIfExists('boat_posts');
    }
}
