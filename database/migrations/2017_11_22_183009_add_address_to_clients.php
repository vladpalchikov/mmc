<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAddressToClients extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clients', function($table) {
            $table->string('organization_address_line2')->nullable();
            $table->string('organization_address_line3')->nullable();
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
        Schema::table('clients', function($table) {
            $table->dropColumn('organization_address_line2');
            $table->dropColumn('organization_address_line3');
            $table->dropColumn('address_line2');
            $table->dropColumn('address_line3');
        });
    }
}
