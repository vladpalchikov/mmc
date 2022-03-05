<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameAddressesFieldsInService extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('foreigner_services', function($table) {
            $table->renameColumn('foreigner_document_address', 'foreigner_address');
            $table->renameColumn('foreigner_document_address_line2', 'foreigner_address_line2');
            $table->renameColumn('foreigner_document_address_line3', 'foreigner_address_line3');
        });

        Schema::table('foreigner_patents', function($table) {
            $table->string('phone')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('foreigner_services', function($table) {
            $table->renameColumn('foreigner_address', 'foreigner_document_address');
            $table->renameColumn('foreigner_address_line2', 'foreigner_document_address_line2');
            $table->renameColumn('foreigner_address_line3', 'foreigner_document_address_line3');
        });

        Schema::table('foreigner_patents', function($table) {
            $table->dropColumn('phone');
        });
    }
}
