<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOrderToServices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('services', function($table) {
            $table->integer('order')->default(0);
        });

        Schema::table('application_services', function($table) {
            $table->integer('service_order')->default(0);
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
            $table->dropColumn('order');
        });

        Schema::table('application_services', function($table) {
            $table->dropColumn('service_order');
        });
    }
}
