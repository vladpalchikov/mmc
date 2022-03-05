<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeForeignerFieldsNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('foreigners', function($table) {
            $table->date('birthday')->nullable()->change();
            $table->string('nationality')->nullable()->change();
            $table->string('document_name')->nullable()->change();
            $table->date('document_date')->nullable()->change();
            $table->date('registration_date')->nullable()->change();
            $table->string('address')->nullable()->change();
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
