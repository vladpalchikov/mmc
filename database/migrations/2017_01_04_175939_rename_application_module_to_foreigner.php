<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameApplicationModuleToForeigner extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('applications', 'foreigners');
        Schema::rename('application_dms', 'foreigner_dms');
        Schema::rename('application_igs', 'foreigner_igs');
        Schema::rename('application_patents', 'foreigner_patents');
        Schema::rename('application_patent_changes', 'foreigner_patent_changes');
        Schema::rename('application_patent_recertifyings', 'foreigner_patent_recertifyings');
        Schema::rename('application_services', 'foreigner_services');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::rename('foreigners', 'applications');
        Schema::rename('foreigner_dms', 'application_dms');
        Schema::rename('foreigner_igs', 'application_igs');
        Schema::rename('foreigner_patents', 'application_patents');
        Schema::rename('foreigner_patent_changes', 'application_patent_changes');
        Schema::rename('foreigner_patent_recertifyings', 'application_patent_recertifyings');
        Schema::rename('foreigner_services', 'application_services');
    }
}
