<?php 

namespace MMC\Console\Commands;

use Illuminate\Console\Command;

use MMC\Models\ForeignerService;
use MMC\Models\ForeignerServiceGroup;
use MMC\Models\MUService;
use MMC\Models\Helper;

use DB;

class ServiceUnion extends Command
{
	/**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mmc:service-union';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Объединение услуг ТМ и МУ';

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
        $this->info('Всего услуг МУ: '.MUService::count());
        foreach (MUService::all() as $muservice) {
            $group = new ForeignerServiceGroup;
            $group->client_id = $muservice->client_id;
            $group->service_id = $muservice->service_id;
            $group->operator_id = $muservice->operator_id;
            $group->cashier_id = $muservice->cashier_id;
            $group->payment_method = $muservice->payment_method;
            $group->payment_status = $muservice->payment_status;
            $group->payment_at = $muservice->payment_at;
            $group->created_at = $muservice->created_at;
            $group->service_count = $muservice->service_count;
            $group->service_name = $muservice->service_name;
            $group->service_price = $muservice->service_price;
            $group->service_description = $muservice->service_description;
            $group->save();
            $this->info('Создана группа id '.$group->id);
            $this->info('Добавлена в группу услуга id '.$muservice->id);
            $this->info('Создано услуг: '.$muservice->service_count);
            $this->info('----------------------------------');

            for ($i = 0; $i < $muservice->service_count; $i++) { 
                $this->createService($muservice, $group->id);
            }

        }
    }

    public function createService($muservice, $group_id = null) {
        $foreignerService = new ForeignerService;
        $foreignerService->service_id = $muservice->service_id;
        $foreignerService->service_description = $muservice->service_description;
        $foreignerService->service_name = $muservice->service_name;
        $foreignerService->service_price = $muservice->service_price;
        $foreignerService->created_at = $muservice->created_at;
        $foreignerService->updated_at = $muservice->updated_at;
        $foreignerService->service_order = $muservice->service_order;
        $foreignerService->operator_id = $muservice->operator_id;
        $foreignerService->payment_status = $muservice->payment_status;
        $foreignerService->cashier_id = $muservice->cashier_id;
        $foreignerService->repayment_status = $muservice->repayment_status;
        $foreignerService->payment_at = $muservice->payment_at;
        $foreignerService->payment_method = $muservice->payment_method;
        $foreignerService->client_id = $muservice->client_id;
        $foreignerService->updated_by = $muservice->updated_by;
        $foreignerService->is_mu = true;
        $foreignerService->group_id = $group_id;
        $foreignerService->save();

        return $foreignerService->id;
    }
}