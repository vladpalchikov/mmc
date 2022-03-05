<?php 

namespace MMC\Console\Commands;

use Illuminate\Console\Command;

use MMC\Models\ForeignerService;
use MMC\Models\MU\MUService;
use MMC\Models\Helper;

use DB;

class ServiceArchive extends Command
{
	/**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mmc:foreigner-cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Очистка старых платежей';

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
        DB::beginTransaction();

        $this->info('Модуль Трудовая миграция');
        $this->info('Наличная оплата');
        $cashDate = date('d.m.y', strtotime(date('d.m.Y').'-1 month'));
        $foreignerServices = ForeignerService::where('is_mu', 0)->where('created_at', '<=', Helper::formatDateForQuery($cashDate))
                            ->where('payment_method', 0)
                            ->where('payment_status', 0);
        $totalSum = ForeignerService::where('is_mu', 0)->where('created_at', '<=', Helper::formatDateForQuery($cashDate))
                    ->where('payment_method', 0)
                    ->where('payment_status', 0)
                    ->sum('service_price');
        $totalCount = ForeignerService::where('is_mu', 0)->where('created_at', '<=', Helper::formatDateForQuery($cashDate))
                    ->where('payment_method', 0)
                    ->where('payment_status', 0)
                    ->count();
        $bar = $this->output->createProgressBar($totalCount);
        $this->info('Услуги старше '.$cashDate);
        $this->info('Найдено: '.$totalCount);
        $this->info('Общая сумма: '.number_format($totalSum, 0, ',', ' ').' руб.');
        $this->info('');
        while (ForeignerService::where('is_mu', 0)->where('created_at', '<=', Helper::formatDateForQuery($cashDate))
            ->where('payment_method', 0)
            ->where('payment_status', 0)
            ->count() > 0) {
            $foreignerServices->chunk(200, function ($serviceChunk) use ($bar) {
                foreach ($serviceChunk as $foreignerService) {
                    $foreignerService->payment_status = 2;
                    $foreignerService->save();
                    $bar->advance();
                }
            });
        }
        $bar->finish();
        $this->info('');
        $this->info('');

        // -----------------------------------------------------------------------------------------

        $this->info('Безналичная оплата');
        $cashlessDate = date('d.m.y', strtotime(date('d.m.Y').'-3 month'));
        $foreignerServices = ForeignerService::where('is_mu', 0)->where('created_at', '<=', Helper::formatDateForQuery($cashlessDate))
                            ->where('payment_method', 1)
                            ->where('payment_status', 0);
        $totalSum = ForeignerService::where('is_mu', 0)->where('created_at', '<=', Helper::formatDateForQuery($cashlessDate))
                    ->where('payment_method', 1)
                    ->where('payment_status', 0)
                    ->sum('service_price');
        $totalCount = ForeignerService::where('is_mu', 0)->where('created_at', '<=', Helper::formatDateForQuery($cashlessDate))
                    ->where('payment_method', 1)
                    ->where('payment_status', 0)
                    ->count();
        $bar = $this->output->createProgressBar($totalCount);
        $this->info('Услуги старше '.$cashlessDate);
        $this->info('Найдено: '.$totalCount);
        $this->info('Общая сумма: '.number_format($totalSum, 0, ',', ' ').' руб.');
        $this->info('');
        while (ForeignerService::where('is_mu', 0)->where('created_at', '<=', Helper::formatDateForQuery($cashlessDate))
            ->where('payment_method', 1)
            ->where('payment_status', 0)
            ->count() > 0) {
            $foreignerServices->chunk(200, function ($serviceChunk) use ($bar) {
                foreach ($serviceChunk as $foreignerService) {
                    $foreignerService->payment_status = 2;
                    $foreignerService->save();
                    $bar->advance();
                }
            });
        }
        $bar->finish();
        $this->info('');
        $this->info('');


        $this->info('Модуль Миграционный учет');
        $this->info('Наличная оплата');
        $cashDate = date('d.m.y', strtotime(date('d.m.Y').'-1 month'));
        $foreignerServices = ForeignerService::where('is_mu', 1)->where('created_at', '<=', Helper::formatDateForQuery($cashDate))
                            ->where('payment_method', 0)
                            ->where('payment_status', 0);
        $totalSum = ForeignerService::where('is_mu', 1)->where('created_at', '<=', Helper::formatDateForQuery($cashDate))
                    ->where('payment_method', 0)
                    ->where('payment_status', 0)
                    ->sum('service_price');
        $totalCount = ForeignerService::where('is_mu', 1)->where('created_at', '<=', Helper::formatDateForQuery($cashDate))
                    ->where('payment_method', 0)
                    ->where('payment_status', 0)
                    ->count();
        $bar = $this->output->createProgressBar($totalCount);
        $this->info('Услуги старше '.$cashDate);
        $this->info('Найдено: '.$totalCount);
        $this->info('Общая сумма: '.number_format($totalSum, 0, ',', ' ').' руб.');
        $this->info('');
        while (ForeignerService::where('is_mu', 1)->where('created_at', '<=', Helper::formatDateForQuery($cashDate))
            ->where('payment_method', 0)
            ->where('payment_status', 0)
            ->count() > 0) {
            $foreignerServices->chunk(200, function ($serviceChunk) use ($bar) {
                foreach ($serviceChunk as $foreignerService) {
                    $foreignerService->payment_status = 2;
                    $foreignerService->save();
                    $group = $foreignerService->group;
                    $group->payment_status = 2;
                    $group->save();
                    $bar->advance();
                }
            });
        }
        $bar->finish();
        $this->info('');
        $this->info('');

        // -----------------------------------------------------------------------------------------

        $this->info('Безналичная оплата');
        $cashlessDate = date('d.m.y', strtotime(date('d.m.Y').'-3 month'));
        $foreignerServices = ForeignerService::where('is_mu', 1)->where('created_at', '<=', Helper::formatDateForQuery($cashlessDate))
                            ->where('payment_method', 1)
                            ->where('payment_status', 0);
        $totalSum = ForeignerService::where('is_mu', 1)->where('created_at', '<=', Helper::formatDateForQuery($cashlessDate))
                    ->where('payment_method', 1)
                    ->where('payment_status', 0)
                    ->sum('service_price');
        $totalCount = ForeignerService::where('is_mu', 1)->where('created_at', '<=', Helper::formatDateForQuery($cashlessDate))
                    ->where('payment_method', 1)
                    ->where('payment_status', 0)
                    ->count();
        $bar = $this->output->createProgressBar($totalCount);
        $this->info('Услуги старше '.$cashlessDate);
        $this->info('Найдено: '.$totalCount);
        $this->info('Общая сумма: '.number_format($totalSum, 0, ',', ' ').' руб.');
        $this->info('');
        while (ForeignerService::where('is_mu', 1)->where('created_at', '<=', Helper::formatDateForQuery($cashlessDate))
            ->where('payment_method', 1)
            ->where('payment_status', 0)
            ->count() > 0) {
            $foreignerServices->chunk(200, function ($serviceChunk) use ($bar) {
                foreach ($serviceChunk as $foreignerService) {
                    $foreignerService->payment_status = 2;
                    $foreignerService->save();
                    $group = $foreignerService->group;
                    $group->payment_status = 2;
                    $group->save();
                    $bar->advance();
                }
            });
        }
        $bar->finish();
        $this->info('');
        $this->info('');

        if ($this->confirm('Архивировать платежи?')) {
            DB::commit();
        } else {
            DB::rollBack();
        }
    }
	
}