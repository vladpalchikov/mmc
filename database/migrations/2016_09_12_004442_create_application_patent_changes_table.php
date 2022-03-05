<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApplicationPatentChangesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('application_patent_changes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('application_id');

            $table->string('surname_change')->nullable();
            $table->string('name_change')->nullable();
            $table->string('middle_name_change')->nullable();

            $table->string('document_name_change')->nullable();
            $table->string('document_series_change')->nullable();
            $table->string('document_number_change')->nullable();
            $table->string('document_issued_change')->nullable();
            $table->date('document_date_change')->nullable();

            $table->string('patent_series_change')->nullable();
            $table->string('patent_number_change')->nullable();

            $table->string('blank_patent_series_change')->nullable();
            $table->string('blank_patent_number_change')->nullable();

            $table->string('profession_change')->nullable();
            $table->string('profession_line2_change')->nullable();

            $table->date('date_change')->nullable();
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
        Schema::drop('application_patent_changes');
    }
}
