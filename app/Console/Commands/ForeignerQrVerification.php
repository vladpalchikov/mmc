<?php

namespace MMC\Console\Commands;

use Illuminate\Console\Command;

use MMC\Models\ForeignerQR;
use Storage;
use DB;

class ForeignerQrVerification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mmc:foreigner-qr-verification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Верификация Foreiger QR';

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
        ForeignerQR::where('is_verified', false)->chunk(1000, function($qrs) {
            foreach ($qrs as $qr) {
                if ($qr->tax) {
                    if ($qr->sum >= $qr->tax->price) {
                        $qr->is_verified = true;
                        $qr->save();
                    }
                }
            }
        });
    }
}