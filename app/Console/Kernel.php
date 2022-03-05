<?php

namespace MMC\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\SearchForeignerDuplicate::class,
        Commands\VendorFix::class,
        Commands\ImportPayments::class,
        Commands\ServiceArchive::class,
        Commands\ServiceUnion::class,
        Commands\CheckInnLock::class,
        Commands\LinkPaymentToForeigners::class,
        Commands\AddComplexToServices::class,
        Commands\AddMuIdToGroups::class,
        Commands\ForeignerQrUnion::class,
        Commands\ForeignerQrVerification::class,
        Commands\ImportForeignerFieldsToPatent::class,
        Commands\ConvertMMC::class,
        Commands\CreateXmlForPatents::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('backup:run')->twiceDaily(13, 20);
        $schedule->command('backup:clean')->weekly();
        $schedule->command('mmc:inn-check-lock')->everyMinute();
        $schedule->command('mmc:foreigner-qr-union')->hourly();
    }
}
