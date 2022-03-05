<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReceivingOrgAddressLine2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('application_igs', function($table) {
            $table->string('receiving_org_name_line2')->nullable();
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
            $table->dropColumn('receiving_org_name_line2');
        });
    }
}
