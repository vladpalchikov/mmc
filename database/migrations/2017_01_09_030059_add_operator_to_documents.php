<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOperatorToDocuments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('foreigner_dms', function($table) {
            $table->integer('operator_id')->nullable();
            $table->integer('updated_by')->nullable();
        });

        Schema::table('foreigner_igs', function($table) {
            $table->integer('operator_id')->nullable();
            $table->integer('updated_by')->nullable();
        });

        Schema::table('foreigner_patents', function($table) {
            $table->integer('operator_id')->nullable();
            $table->integer('updated_by')->nullable();
        });

        Schema::table('foreigner_patent_changes', function($table) {
            $table->integer('operator_id')->nullable();
            $table->integer('updated_by')->nullable();
        });

        Schema::table('foreigner_patent_recertifyings', function($table) {
            $table->integer('operator_id')->nullable();
            $table->integer('updated_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('foreigner_dms', function($table) {
            $table->dropColumn('operator_id');
            $table->dropColumn('updated_by');
        });

        Schema::table('foreigner_igs', function($table) {
            $table->dropColumn('operator_id');
            $table->dropColumn('updated_by');
        });

        Schema::table('foreigner_patents', function($table) {
            $table->dropColumn('operator_id');
            $table->dropColumn('updated_by');
        });

        Schema::table('foreigner_patent_changes', function($table) {
            $table->dropColumn('operator_id');
            $table->dropColumn('updated_by');
        });

        Schema::table('foreigner_patent_recertifyings', function($table) {
            $table->dropColumn('operator_id');
            $table->dropColumn('updated_by');
        });
    }
}
