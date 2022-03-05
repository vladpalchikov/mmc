<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ReplaceApplicationId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('foreigner_dms', function($table) {
            $table->renameColumn('application_id', 'foreigner_id');
        });

        Schema::table('foreigner_igs', function($table) {
            $table->renameColumn('application_id', 'foreigner_id');
        });

        Schema::table('foreigner_patents', function($table) {
            $table->renameColumn('application_id', 'foreigner_id');
        });

        Schema::table('foreigner_patent_changes', function($table) {
            $table->renameColumn('application_id', 'foreigner_id');
        });

        Schema::table('foreigner_patent_recertifyings', function($table) {
            $table->renameColumn('application_id', 'foreigner_id');
        });

        Schema::table('foreigner_services', function($table) {
            $table->renameColumn('application_id', 'foreigner_id');
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
            $table->renameColumn('foreigner_id', 'application_id');
        });

        Schema::table('foreigner_igs', function($table) {
            $table->renameColumn('foreigner_id', 'application_id');
        });

        Schema::table('foreigner_patents', function($table) {
            $table->renameColumn('foreigner_id', 'application_id');
        });

        Schema::table('foreigner_patent_changes', function($table) {
            $table->renameColumn('foreigner_id', 'application_id');
        });

        Schema::table('foreigner_patent_recertifyings', function($table) {
            $table->renameColumn('foreigner_id', 'application_id');
        });

        Schema::table('foreigner_services', function($table) {
            $table->renameColumn('foreigner_id', 'application_id');
        });
    }
}
