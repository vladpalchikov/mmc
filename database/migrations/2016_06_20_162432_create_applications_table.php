<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->increments('id');
            $table->string('surname');
            $table->string('name');
            $table->string('middle_name');
            $table->date('birthday');
            $table->string('nationality');
            $table->string('document_name');
            $table->string('document_series')->nullable();
            $table->string('document_number');
            $table->date('document_date');
            $table->date('registration_date');
            $table->boolean('gender')->default(0);
            $table->string('address');
            $table->string('phone');
            $table->integer('status')->default(0);
            $table->integer('operator_id');
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
        Schema::drop('applications');
    }
}
