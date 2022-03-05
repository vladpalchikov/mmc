<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAdditionalLines extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('applications', function($table) {
            $table->string('address_line2')->nullable();
            $table->string('address_line3')->nullable();
        });

        Schema::table('application_patents', function($table) {
            $table->string('registration_address_line2')->nullable();
            $table->string('russian_document_line2')->nullable();
            $table->string('profession_line2')->nullable();
            $table->string('prev_patent_line2')->nullable();
        });

        Schema::table('application_igs', function($table) {
            $table->string('receiving_org_address_line2')->nullable();
            $table->string('representatives_line2')->nullable();
            $table->string('representatives_line3')->nullable();
            $table->string('representatives_line4')->nullable();
            $table->string('representatives_line5')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('applications', function($table) {
            $table->dropColumn('address_line2');
            $table->dropColumn('address_line3');
        });

        Schema::table('application_patents', function($table) {
            $table->dropColumn('registration_address_line2');
            $table->dropColumn('russian_document_line2');
            $table->dropColumn('profession_line2');
            $table->dropColumn('prev_patent_line2');
        });

        Schema::table('application_igs', function($table) {
            $table->dropColumn('receiving_org_address_line2');
            $table->dropColumn('representatives_line2');
            $table->dropColumn('representatives_line3');
            $table->dropColumn('representatives_line4');
            $table->dropColumn('representatives_line5');
        });
    }
}
