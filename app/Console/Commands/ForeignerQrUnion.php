<?php

namespace MMC\Console\Commands;

use Illuminate\Console\Command;

use MMC\Models\ForeignerQR;
use Storage;
use DB;

class ForeignerQrUnion extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mmc:foreigner-qr-union';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Объединение Foreiger QR';

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
        $this->info('Всего QR: '.ForeignerQR::count());
        $qrs = DB::select(DB::raw('select txn_id from `foreigner_qrs` group by txn_id HAVING COUNT(*) > 1'));
        foreach ($qrs as $qr) {
            if ($qr->txn_id) {
                if (ForeignerQR::where('txn_id', $qr->txn_id)->count() > 1) {
                    $duplicates = ForeignerQR::where('txn_id', $qr->txn_id)->skip(1)->take(100)->orderBy('created_at', 'desc')->get();

                    foreach ($duplicates as $duplicate) {
                        $duplicate->delete();
                    }
                }
            }
        }
    }
}