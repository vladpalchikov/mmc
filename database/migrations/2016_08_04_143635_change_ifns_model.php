<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeIfnsModel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('ifns', 'districts');
        Schema::create('ifns', function (Blueprint $table) {
            $table->increments('id');
            $table->string('kod');
            $table->string('inn');
            $table->string('name');
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
        Schema::rename('districts', 'ifns');
        Schema::drop('ifns');
    }
}
