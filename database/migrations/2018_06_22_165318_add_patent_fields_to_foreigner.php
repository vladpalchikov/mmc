<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPatentFieldsToForeigner extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('foreigners', function($table) {
            $table->string('name_change')->nullable();
            $table->string('birthday_place')->nullable();
            $table->string('registration_address')->nullable();
            $table->string('registration_address_line2')->nullable();
            $table->string('russian_document')->nullable();
            $table->string('russian_number')->nullable();
            $table->string('russian_series')->nullable();
            $table->date('russian_date')->nullable();
            $table->string('work_activity')->nullable();
            $table->string('prev_patent')->nullable();
            $table->string('prev_patent_series')->nullable();
            $table->string('prev_patent_number')->nullable();
            $table->string('prev_patent_blank_series')->nullable();
            $table->string('prev_patent_blank_number')->nullable();
            $table->date('prev_patent_date_from')->nullable();
            $table->date('prev_patent_date_to')->nullable();
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
            $table->dropColumn('name_change');
            $table->dropColumn('birthday_place');
            $table->dropColumn('registration_address');
            $table->dropColumn('registration_address_line2');
            $table->dropColumn('russian_document');
            $table->dropColumn('russian_number');
            $table->dropColumn('russian_series');
            $table->dropColumn('russian_date');
            $table->dropColumn('work_activity');
            $table->dropColumn('prev_patent');
            $table->dropColumn('prev_patent_series');
            $table->dropColumn('prev_patent_number');
            $table->dropColumn('prev_patent_blank_series');
            $table->dropColumn('prev_patent_blank_number');
            $table->dropColumn('prev_patent_date_from');
            $table->dropColumn('prev_patent_date_to');
        });
    }
}
