<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddServicesFieldsToGroups extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('foreigner_service_groups', function($table) {
            $table->string('service_name')->nullable();
            $table->float('service_price')->default(0);
            $table->text('service_description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('foreigner_service_groups', function($table) {
            $table->dropColumn('service_name');
            $table->dropColumn('service_price');
            $table->dropColumn('service_description');
        });
    }
}
