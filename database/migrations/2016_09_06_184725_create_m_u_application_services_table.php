<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMUApplicationServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_u_application_services', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('application_id');
            $table->integer('service_id');
            $table->string('service_description');
            $table->string('service_name');
            $table->float('service_price')->default(0);
            $table->integer('service_order')->default(0);
            $table->integer('operator_id')->nullable();
            $table->boolean('payment_status')->default(0);
            $table->integer('cashier_id')->nullable();
            $table->integer('repayment_status')->default(0);
            $table->integer('service_count')->default(0);
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
        Schema::drop('m_u_application_services');
    }
}
