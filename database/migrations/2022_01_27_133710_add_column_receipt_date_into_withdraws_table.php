<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnReceiptDateIntoWithdrawsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('withdraws', 'receipt_date')) {
            Schema::table('withdraws', function (Blueprint $table) {
                $table->date('receipt_date')->nullable()->after('receipt_url');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('withdraws', 'receipt_date')) {
            Schema::table('withdraws', function (Blueprint $table) {
                $table->dropColumn('receipt_date');
            });
        }
    }
}
