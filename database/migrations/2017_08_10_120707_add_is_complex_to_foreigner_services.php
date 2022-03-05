<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsComplexToForeignerServices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('foreigner_services', function($table) {
            $table->boolean('is_complex')->default(0);
        });

        Schema::table('foreigner_service_groups', function($table) {
            $table->boolean('is_complex')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('foreigner_services', function($table) {
            $table->dropColumn('is_complex');
        });

        Schema::table('foreigner_service_groups', function($table) {
            $table->dropColumn('is_complex');
        });
    }
}
