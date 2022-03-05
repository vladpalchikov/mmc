<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApplicationIgsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('application_igs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('application_id');

            $table->string('place_birthday_country')->nullable();
            $table->string('place_birthday_city')->nullable();
            $table->integer('residence_type')->default(0);
            $table->string('residence_series')->nullable();
            $table->string('residence_number')->nullable();
            $table->date('residence_date_from')->nullable();
            $table->date('residence_date_to')->nullable();

            $table->integer('entry_purpose')->default(0);

            $table->string('profession')->nullable();
            $table->string('qualification')->nullable();

            $table->date('enter_date_from')->nullable();
            $table->date('enter_date_to')->nullable();

            $table->string('migration_card_series')->nullable();
            $table->string('migration_card_number')->nullable();

            $table->string('area')->nullable();
            $table->string('region')->nullable();
            $table->string('city')->nullable();
            $table->string('street')->nullable();
            $table->string('house')->nullable();
            $table->string('housing')->nullable();
            $table->string('building')->nullable();
            $table->string('flat')->nullable();

            $table->string('place_area')->nullable();
            $table->string('place_region')->nullable();
            $table->string('place_city')->nullable();
            $table->string('place_street')->nullable();
            $table->string('place_house')->nullable();
            $table->string('place_housing')->nullable();
            $table->string('place_building')->nullable();
            $table->string('place_flat')->nullable();
            $table->string('place_phone')->nullable();

            $table->text('representatives')->nullable();

            $table->boolean('receiving_type')->default(0);
            $table->string('receiving_surname')->nullable();
            $table->string('receiving_name')->nullable();
            $table->string('receiving_middle_name')->nullable();
            $table->string('receiving_birthday')->nullable();
            $table->string('receiving_document_name')->nullable();
            $table->string('receiving_document_series')->nullable();
            $table->string('receiving_document_number')->nullable();
            $table->date('receiving_document_date_from')->nullable();
            $table->date('receiving_document_date_to')->nullable();
            $table->string('receiving_area')->nullable();
            $table->string('receiving_region')->nullable();
            $table->string('receiving_city')->nullable();
            $table->string('receiving_street')->nullable();
            $table->string('receiving_house')->nullable();
            $table->string('receiving_housing')->nullable();
            $table->string('receiving_building')->nullable();
            $table->string('receiving_flat')->nullable();
            $table->string('receiving_phone')->nullable();
            $table->string('receiving_org_name')->nullable();
            $table->string('receiving_org_address')->nullable();
            $table->string('receiving_org_inn')->nullable();

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
        Schema::drop('application_igs');
    }
}
