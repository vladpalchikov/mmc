<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeTaxLink extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('taxes', function($table) {
            $table->dropColumn('service_id');
        });

        Schema::table('services', function($table) {
            $table->integer('tax_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('taxes', function($table) {
            $table->integer('service_id')->nullable();
        });

        Schema::table('services', function($table) {
            $table->dropColumn('tax_id');
        });
    }
}
