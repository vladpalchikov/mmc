<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldToQr extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('foreigner_qrs', function($table) {
            $table->string('sum_from')->nullable();
            $table->dateTime('status_datetime')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('foreigner_qrs', function($table) {
            $table->dropColumn('sum_from');
            $table->dropColumn('status_datetime');
        });
    }
}
