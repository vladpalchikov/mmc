<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddClientAndPaymentToApplicationServices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('application_services', function($table) {
            $table->boolean('payment_method')->default(0);
            $table->integer('client_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('application_services', function($table) {
            $table->boolean('payment_method')->default(0);
            $table->integer('client_id')->nullable();
        });
    }
}
