<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateForeignerServiceGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('foreigner_service_groups', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('client_id')->nullable();
            $table->integer('service_id')->nullable();
            $table->integer('operator_id')->nullable();
            $table->integer('cashier_id')->nullable();
            $table->boolean('payment_method')->default(0);
            $table->integer('payment_status')->default(0);
            $table->integer('service_count')->default(0);
            $table->datetime('payment_at')->nullable();
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
        Schema::dropIfExists('foreigner_service_groups');
    }
}
