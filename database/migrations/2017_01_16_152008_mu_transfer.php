<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MuTransfer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('m_u_application_services', function($table) {
            $table->integer('client_id')->nullable();
            $table->integer('updated_by')->nullable();
            $table->integer('payment_method')->nullable();
        });

        Schema::rename('m_u_application_services', 'mu_services');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::rename('mu_services', 'm_u_application_services');
        Schema::table('m_u_application_services', function($table) {
            $table->dropColumn('operator_id');
            $table->dropColumn('client_id');
            $table->dropColumn('updated_by');
            $table->dropColumn('payment_method');
        });

    }
}
