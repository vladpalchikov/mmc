<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddHostToForeigners extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('foreigners', function($table) {
            $table->integer('host_id')->nullable();
            $table->boolean('is_host_available')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('foreigners', function($table) {
            $table->dropColumn('host_id');
            $table->dropColumn('is_host_available');
        });
    }
}
