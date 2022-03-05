<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPaymentAtToAppServices2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('application_services', 'payment_at')) {
            Schema::table('application_services', function($table) {
                $table->dateTime('payment_at')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('application_services', 'payment_at')) {
            Schema::table('application_services', function($table) {
                $table->dropColumn('payment_at');
            });
        }
    }
}
