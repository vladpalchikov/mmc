<?php 

namespace MMC\Console\Commands;

use Illuminate\Console\Command;

use MMC\Models\ForeignerService;
use MMC\Models\ForeignerServiceGroup;
use MMC\Models\MUService;
use MMC\Models\Helper;

use DB;

class AddComplexToServices extends Command
{
	/**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mmc:service-add-complex';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Добавление is_complex для старых услуг';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        ForeignerService::chunk(200, function($services) {
            foreach ($services as $service) {
                $this->info($service->id);
                if ($service->service) {
                    $service->is_complex = $service->service->is_complex;
                    $service->save();

                    if ($service->group_id) {
                        $group = $service->group;
                        $group->is_complex = $service->is_complex;
                        $group->save();
                    }
                }
            }
        });
    }
}