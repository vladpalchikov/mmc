<?php 

namespace MMC\Console\Commands;

use Illuminate\Console\Command;
use MMC\Models\Foreigner;

class SearchForeignerDuplicate extends Command
{
	/**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mmc:foreigner-duplicates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Поиск и удаление дубликатов ИГ';

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
    	$this->info('Поиск...');
        $duplicates = [];
        $foreigners = \DB::select("SELECT *, COUNT(*) c FROM foreigners GROUP BY CONCAT(document_series,document_number) HAVING c > 1;");
        foreach ($foreigners as $foreigner) {
            $duplicate = Foreigner::where('document_series', '=', $foreigner->document_series)->where('document_number', '=', $foreigner->document_number)->orderBy('created_at', 'desc');
            $duplicateOriginal = Foreigner::where('document_series', '=', $foreigner->document_series)->where('document_number', '=', $foreigner->document_number)->orderBy('created_at', 'desc');

            $duplicateCount = $duplicate->count();
            if ($duplicateCount > 1) {
                foreach ($duplicate->skip(1)->take($duplicateCount)->get() as $duplicateApp) {
                    $duplicates[$duplicateApp->id] = $duplicateApp;
                    $duplicates[$duplicateApp->id]->new_app_id = $duplicateOriginal->first()->id;
                }
            }
        }

        $this->info('Найдено дубликатов: '.count($duplicates));

        $duplicatesText = '';
        if (count($duplicates) > 0) {
            foreach ($duplicates as $duplicate) {
                $this->info('Объединение услуг из дубликатов...');
                foreach ($duplicate->services as $service) {
                    $service->foreigner_id = $duplicate->new_app_id;
                    $service->save();
                }

                $this->info('Объединение платежей из дубликатов...');
                foreach ($duplicate->qr as $qr) {
                    $qr->foreigner_id = $duplicate->new_app_id;
                    $qr->save();
                }

                $this->info('Объединение истории изменений из дубликатов...');
                foreach ($duplicate->history as $history) {
                    $history->foreigner_id = $duplicate->new_app_id;
                    $history->save();
                }

                $duplicate->delete();
                $this->info('Удален: '.$duplicate->document_series.$duplicate->document_number.' - '.$duplicate->surname.' '.$duplicate->name.' '.$duplicate->middle_name.' - '.$duplicate->created_at);
                $duplicatesText .= $duplicate->document_series.$duplicate->document_number.' - '.$duplicate->surname.' '.$duplicate->name.' '.$duplicate->middle_name.' - '.$duplicate->created_at."\n";
            }
        }

        \Storage::put('duplicates_'.date('H.i_d.m.Y').'.txt', $duplicatesText);
    }
	
}