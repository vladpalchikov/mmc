<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToClient extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clients', function($table) {
            $table->boolean('type')->default(0);
            $table->text('organization_form')->nullable();
            $table->text('organization_fullname')->nullable();
            $table->text('organization_inn')->nullable();
            $table->text('organization_address')->nullable();
            $table->text('organization_manager')->nullable();
            $table->text('organization_contact_person')->nullable();
            $table->text('organization_contact_phone')->nullable();
            $table->text('organization_requisite_inn')->nullable();
            $table->text('organization_requisite_account')->nullable();
            $table->text('organization_requisite_bank')->nullable();
            $table->text('organization_requisite_bik')->nullable();
            $table->text('organization_requisite_city')->nullable();
            $table->text('organization_requisite_correspondent')->nullable();
            
            $table->text('person_fullname')->nullable();
            $table->date('person_birthday')->nullable();
            $table->text('person_document')->nullable();
            $table->text('person_document_series')->nullable();
            $table->text('person_document_number')->nullable();
            $table->date('person_document_date')->nullable();
            $table->text('person_document_issuedby')->nullable();
            $table->text('person_document_address')->nullable();
            $table->text('person_document_phone')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clients', function($table) {
            $table->dropColumn('type');
            $table->dropColumn('organization_form');
            $table->dropColumn('organization_fullname');
            $table->dropColumn('organization_inn');
            $table->dropColumn('organization_address');
            $table->dropColumn('organization_manager');
            $table->dropColumn('organization_contact_person');
            $table->dropColumn('organization_contact_phone');
            $table->dropColumn('organization_requisite_inn');
            $table->dropColumn('organization_requisite_account');
            $table->dropColumn('organization_requisite_bank');
            $table->dropColumn('organization_requisite_bik');
            $table->dropColumn('organization_requisite_city');
            $table->dropColumn('organization_requisite_correspondent');
            
            $table->dropColumn('person_fullname');
            $table->dropColumn('person_birthday');
            $table->dropColumn('person_document');
            $table->dropColumn('person_document_series');
            $table->dropColumn('person_document_number');
            $table->dropColumn('person_document_date');
            $table->dropColumn('person_document_issuedby');
            $table->dropColumn('person_document_address');
            $table->dropColumn('person_document_phone');
        });
    }
}
