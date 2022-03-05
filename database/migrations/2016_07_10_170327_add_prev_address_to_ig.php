<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPrevAddressToIg extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('application_igs', function($table) {
            $table->string('prev_address_line1')->nullable();
            $table->string('prev_address_line2')->nullable();
            $table->string('prev_address_line3')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('application_igs', function($table) {
            $table->dropColumn('prev_address_line1');
            $table->dropColumn('prev_address_line2');
            $table->dropColumn('prev_address_line3');
        });
    }
}
