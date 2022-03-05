<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddServiceFees extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('services', function($table) {
            $table->float('agent_compensation')->default(0);
            $table->float('principal_sum')->default(0);
        });

        Schema::table('application_services', function($table) {
            $table->float('service_agent_compensation')->default(0);
            $table->float('service_principal_sum')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('services', function($table) {
            $table->dropColumn('agent_compensation');
            $table->dropColumn('principal_sum');
        });

        Schema::table('application_services', function($table) {
            $table->dropColumn('service_agent_compensation');
            $table->dropColumn('service_principal_sum');
        });
    }
}
