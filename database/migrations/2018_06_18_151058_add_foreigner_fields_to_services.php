<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignerFieldsToServices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('foreigner_services', function($table) {
            $table->string('foreigner_surname')->nullable();
            $table->string('foreigner_name')->nullable();
            $table->string('foreigner_middle_name')->nullable();
            $table->date('foreigner_birthday')->nullable();
            $table->boolean('foreigner_gender')->nullable();
            $table->string('foreigner_nationality')->nullable();
            $table->string('foreigner_nationality_line2')->nullable();
            $table->string('foreigner_document_name')->nullable();
            $table->string('foreigner_document_series')->nullable();
            $table->string('foreigner_document_number')->nullable();
            $table->date('foreigner_document_date')->nullable();
            $table->string('foreigner_document_issuedby')->nullable();
            $table->string('foreigner_document_address')->nullable();
            $table->string('foreigner_document_address_line2')->nullable();
            $table->string('foreigner_document_address_line3')->nullable();
            $table->date('foreigner_registration_date')->nullable();
            $table->string('foreigner_phone')->nullable();
            $table->string('foreigner_oktmo')->nullable();
            $table->string('foreigner_ifns_name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
