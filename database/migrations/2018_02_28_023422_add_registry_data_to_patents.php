<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRegistryDataToPatents extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('foreigner_patents', function($table) {
            $table->integer('doc_status')->default(0);
            $table->dateTime('reg_at')->nullable();
            $table->dateTime('conf_at')->nullable();
            $table->integer('uo_user')->nullable();
        });

        Schema::table('foreigner_patent_recertifyings', function($table) {
            $table->integer('doc_status')->default(0);
            $table->dateTime('reg_at')->nullable();
            $table->dateTime('conf_at')->nullable();
            $table->integer('uo_user')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('foreigner_patents', function($table) {
            $table->dropColumn('doc_status');
            $table->dropColumn('reg_at');
            $table->dropColumn('conf_at');
            $table->dropColumn('uo_user');
        });

        Schema::table('foreigner_patent_recertifyings', function($table) {
            $table->dropColumn('doc_status');
            $table->dropColumn('reg_at');
            $table->dropColumn('conf_at');
            $table->dropColumn('uo_user');
        });
    }
}
