<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexesToPatents extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('foreigner_patents', function($table) {
            $table->index('created_at');
            $table->index('operator_id');
            $table->index('foreigner_id');
            $table->index(['created_at', 'operator_id', 'foreigner_id'], 'main_patent_index');
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
            $table->dropIndex('created_at');
            $table->dropIndex('operator_id');
            $table->dropIndex('foreigner_id');
            $table->dropIndex('main_patent_index');
        });
    }
}
