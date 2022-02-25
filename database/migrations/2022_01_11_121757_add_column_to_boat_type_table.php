<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToBoatTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('boat_types', function (Blueprint $table) {
            $table->boolean('is_active')->after('pic')->default(true);
            $table->boolean('is_deleted')->after('is_active')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('Boat_types', function (Blueprint $table) {
            $table->dropColumn('is_active');
            $table->dropColumn('is_deleted');
        });
    }
}
