<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApplicationServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('application_services', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('application_id');
            $table->integer('service_id');
            $table->string('service_description');
            $table->string('service_name');
            $table->float('service_price')->default(0);
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
        Schema::drop('application_services');
    }
}
