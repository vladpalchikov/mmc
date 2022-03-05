<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexToServicesGroups extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('foreigner_service_groups', function($table) {
            $table->index('created_at');
            $table->index('operator_id');
            $table->index('client_id');
            $table->index('payment_at');
            $table->index('cashier_id');
            $table->index('payment_status');
            $table->index(['operator_id', 'payment_status'], 'total_payed_index');
            $table->index(['operator_id', 'payment_status', 'payment_method'], 'payment_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('foreigner_service_groups', function($table) {
            $table->dropIndex(['created_at']);
            $table->dropIndex(['operator_id']);
            $table->dropIndex(['payment_status']);
            $table->dropIndex('total_payed_index');
            $table->dropIndex('payment_index');
        });
    }
}
