<?php 

namespace MMC\Console\Commands;

use Illuminate\Console\Command;

use MMC\Models\ForeignerService;
use MMC\Models\ForeignerServiceGroup;
use MMC\Models\MUService;
use MMC\Models\Helper;

use DB;

class AddMuIdToGroups extends Command
{
	/**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mmc:service-add-muid';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Добавление mu_id для старых услуг';

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
        foreach(ForeignerServiceGroup::all() as $group) {
            $group->id = $group->id + 20000;
            $group->save();
        }

        foreach(MUService::all() as $muservice) {
            $group = ForeignerServiceGroup::where('client_id', $muservice->client_id)
                                            // ->whereDate('created_at', date('Y-m-d', strtotime($muservice->created_at)))
                                            ->where('created_at', $muservice->created_at)
                                            ->where('service_id', $muservice->service_id)
                                            ->where('service_count', $muservice->service_count)
                                            ->where('operator_id', $muservice->operator_id)
                                            ->where('payment_status', $muservice->payment_status)
                                            ->first();
            $group->id = $muservice->id;
            $group->save();
        }
    }
}