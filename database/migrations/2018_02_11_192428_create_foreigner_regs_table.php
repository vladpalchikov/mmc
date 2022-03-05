<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateForeignerRegsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('foreigner_regs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type');
            $table->integer('foreigner_id');
            $table->integer('client_id')->nullable();
            $table->integer('operator_id');
            $table->text('foreigner_address');
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
        Schema::dropIfExists('foreigner_regs');
    }
}
