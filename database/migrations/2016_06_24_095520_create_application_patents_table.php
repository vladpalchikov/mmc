<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApplicationPatentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('application_patents', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('application_id');

            $table->text('name_change')->nullable();
            $table->text('birthday_place')->nullable();

            $table->text('migration_card_number')->nullable();
            $table->date('migration_card_date')->nullable();

            $table->text('registration_address')->nullable();
            $table->date('registration_date_from')->nullable();
            $table->date('registration_date_to')->nullable();
            $table->text('document_organization')->nullable();
            
            $table->text('inn')->nullable();
            $table->date('inn_date')->nullable();

            $table->text('russian_document')->nullable();
            $table->text('russian_number')->nullable();
            $table->text('russian_series')->nullable();
            $table->date('russian_date')->nullable();

            $table->boolean('work_activity')->default(0);

            $table->text('profession')->nullable();

            $table->date('work_until')->nullable();

            $table->text('prev_patent')->nullable();
            $table->text('prev_patent_series')->nullable();
            $table->text('prev_patent_number')->nullable();

            $table->text('prev_patent_blank_series')->nullable();
            $table->text('prev_patent_blank_number')->nullable();

            $table->date('prev_patent_date_from')->nullable();
            $table->date('prev_patent_date_to')->nullable();

            $table->integer('application_from')->nullable();

            $table->date('document_date_incoming')->nullable();

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
        Schema::drop('application_patents');
    }
}
