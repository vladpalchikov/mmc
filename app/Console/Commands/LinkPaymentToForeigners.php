<?php

namespace MMC\Console\Commands;

use Illuminate\Console\Command;
use \MMC\Models\Foreigner;
use \MMC\Models\ForeignerQR;

class LinkPaymentToForeigners extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mmc:payments-link';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Добавление нераспознанных платежей к ИГ';

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
        $this->info('Всего нераспознанных платежей: '.ForeignerQR::whereNull('foreigner_id')->count());
        foreach (ForeignerQR::whereNull('foreigner_id')->get() as $qr) {
            $this->info($qr->document);
            $series = preg_replace('/[^a-zA-Zа-яА-Я]/', '', $qr->document);
            $number = preg_replace('/[^0-9]/', '', $qr->document);
            if (empty($series)) {
                $foreigner = Foreigner::whereNull('document_series')->where('document_number', $number);
            } else {
                $foreigner = Foreigner::where('document_series', $series)->where('document_number', $number);
            }
            if ($foreigner->count() > 0) {
                $this->info('Найден ИГ для платежа: '.$qr->id);
                $qr->foreigner_id = $foreigner->first()->id;
                $qr->save();
            }
        }
    }
}
