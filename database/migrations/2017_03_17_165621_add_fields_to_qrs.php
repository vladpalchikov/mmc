<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToQrs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('foreigner_qrs', function($table) {
            $table->string('document')->nullable();
            $table->string('inn')->nullable();
            $table->string('address')->nullable();
            $table->string('fio')->nullable();
            $table->string('oktmo')->nullable();
            $table->string('prv_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('foreigner_qrs', function($table) {
            $table->dropColumn('document');
            $table->dropColumn('inn');
            $table->dropColumn('address');
            $table->dropColumn('fio');
            $table->dropColumn('oktmo');
            $table->dropColumn('prv_id');
        });
    }
}
