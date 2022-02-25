<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBoatStoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('boat_stories', function (Blueprint $table) {
            $table->id();
            $table->uuid('story_uuid')->unique();
            $table->foreignId('boat_id')->constrained('boats');
            $table->text('text')->nullable();
            $table->string('story_image')->nullable();
            $table->string('story_video')->nullable();
            $table->string('video_thumbnail')->nullable();
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
        Schema::dropIfExists('boat_stories');
    }
}
