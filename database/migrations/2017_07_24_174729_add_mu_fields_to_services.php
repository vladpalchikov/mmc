<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMuFieldsToServices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('foreigner_services', function($table) {
            $table->integer('updated_by')->nullable();
            $table->boolean('is_mu')->default(0);
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
            $table->dropColumn('updated_by');
            $table->dropColumn('is_mu');
        });
    }
}
