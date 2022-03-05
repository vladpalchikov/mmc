<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignerFieldsToPatent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('foreigner_patents', function($table) {
            $table->string('surname')->nullable();
            $table->string('name')->nullable();
            $table->string('middle_name')->nullable();
            $table->date('birthday')->nullable();
            $table->boolean('gender')->nullable();
            $table->string('nationality')->nullable();
            $table->string('nationality_line2')->nullable();
            $table->string('document_name')->nullable();
            $table->string('document_series')->nullable();
            $table->string('document_number')->nullable();
            $table->date('document_date')->nullable();
            $table->string('address')->nullable();
            $table->string('address_line2')->nullable();
            $table->string('address_line3')->nullable();
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
            $table->dropColumn('surname');
            $table->dropColumn('name');
            $table->dropColumn('middle_name');
            $table->dropColumn('birthday');
            $table->dropColumn('gender');
            $table->dropColumn('nationality');
            $table->dropColumn('nationality_line2');
            $table->dropColumn('document_name');
            $table->dropColumn('document_series');
            $table->dropColumn('document_number');
            $table->dropColumn('document_date');
            $table->dropColumn('address');
            $table->dropColumn('address_line2');
            $table->dropColumn('address_line3');
            $table->dropColumn('inn');
        });
    }
}
