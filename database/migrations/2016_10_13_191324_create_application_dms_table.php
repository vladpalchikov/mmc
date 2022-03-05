<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApplicationDmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('application_dms', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('application_id');
            
            $table->string('dms_surname');
            $table->string('dms_name');
            $table->string('dms_middle_name');

            $table->boolean('dms_gender')->default(0);
            $table->string('dms_nationality');
            $table->date('dms_birthday');

            $table->string('dms_address');
            $table->string('dms_address_line2');
            $table->string('dms_address_line3');

            $table->date('dms_registration_date');

            $table->string('dms_document');
            $table->string('dms_document_series');
            $table->string('dms_document_number');
            $table->string('dms_document_date');
            $table->string('dms_document_issuedby');

            $table->date('dms_registration_ip_date');
            $table->string('dms_registration_document');

            $table->integer('dms_payment')->default(0);
            $table->text('dms_receipt');

            $table->date('dms_contract_date');
            $table->date('dms_policy_date_from');
            $table->date('dms_policy_date_to');

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
        Schema::drop('application_dms');
    }
}
