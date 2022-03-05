<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateForeignerHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('foreigner_histories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('foreigner_id');
            $table->integer('operator_id');

            $table->string('surname');
            $table->string('name');
            $table->string('middle_name')->nullable();

            $table->date('birthday')->nullable();

            $table->string('nationality')->nullable();
            $table->string('nationality_line2')->nullable();

            $table->boolean('gender')->default(0);
            
            $table->string('document_name')->nullable();
            $table->string('document_series')->nullable();
            $table->string('document_number')->nullable();
            $table->date('document_date')->nullable();
            $table->string('document_issuedby')->nullable();

            $table->string('phone')->nullable();

            $table->string('inn')->nullable();
            $table->date('inn_date')->nullable();

            $table->string('address')->nullable();
            $table->string('address_line2')->nullable();
            $table->string('address_line3')->nullable();

            $table->date('registration_date')->nullable();

            $table->string('oktmo')->nullable();

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
        Schema::dropIfExists('foreigner_histories');
    }
}
