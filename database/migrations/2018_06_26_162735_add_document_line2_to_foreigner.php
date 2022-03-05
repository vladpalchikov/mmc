<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDocumentLine2ToForeigner extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('foreigners', function($table) {
            $table->string('russian_document_line2')->nullable();
            $table->string('prev_patent_line2')->nullable();
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
            $table->dropColumn('russian_document_line2');
        });
    }
}
