<?php

namespace MMC\Console\Commands;

use Illuminate\Console\Command;
use MMC\Models\Setting;

class CheckInnLock extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mmc:inn-check-lock';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '[Служебный] Проверка блокировки получения ИНН';

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
        if (Setting::getSetting('inn_success')) {
            $datetime = strtotime(Setting::getSetting('inn_success'));
            $now = time();
            if (($now - $datetime) > 120) { // 2 minutes diff
                Setting::saveSetting('inn_lock', 'false');
            }
        }
    }
}
