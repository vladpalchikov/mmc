<?php

namespace MMC\Console\Commands;

use Illuminate\Console\Command;

use Storage;
use Excel;

use MMC\Models\Foreigner;
use MMC\Models\ForeignerQR;

use DB;

class ImportPayments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mmc:import-payments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Импорт платежей из файлов';

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
        $this->info('Начало импорта...');
        $paymentsCount = 0;
        $totalPayments = 0;
        $totalPaymentsImport = 0;
        $totalPaymentsImportWithoutIg = 0;
        $totalPaymentsImportWithIg = 0;
        $allowedProviders = [
            'Касса ММЦ',
            'ММЦ пошлина гражданство РФ',
            'ММЦ пошлина вид на жительство',
            'ММЦ пошлина приглашение',
            'ММЦ пошлина временное проживание',
            'ММЦ пошлина выдача визы',
            'ММЦ пошлина выдача мультивизы'
        ];
        DB::beginTransaction();
        foreach (Storage::disk('payments')->files() as $file) {
            $url = '/storage/imports/payments/'.$file;
            $this->info('Файл: '.$file);
            $results = Excel::load($url)->toArray();
            $steps = count($results);
            $iterator = 1;
            foreach ($results as $row) {
                if (in_array($row['provayder'], $allowedProviders)) {
                    $search = trim((string) $row['nomer']);
                    $search = preg_replace('/\s+/', ' ', $search);
                    $search = str_replace(' ', '', $search);

                    $foreigner = new Foreigner;
                    $foreigner = $foreigner
                        ->orWhereRaw('CONCAT_WS("",document_series,document_number)="'.$search.'"')
                        ->orWhere('phone', '=', $search);

                    if ($foreigner->count() > 0) {
                        $foreigner = $foreigner->first();
                        $document = $foreigner->document_series.$foreigner->document_number;
                    }

                    if (ForeignerQR::where('transaction', $row['tranzaktsiya'])->count() == 0) {
                        $foreignerQr = new ForeignerQR;
                        $foreignerQr->foreigner_id = $foreigner->count() > 0 ? $foreigner->id : null;
                        $foreignerQr->status = 1;
                        $foreignerQr->status_datetime = date('Y-m-d H:i:s', strtotime($row['data_kvitantsii'].' '.$row['vremya']));
                        $foreignerQr->created_at = date('Y-m-d H:i:s', strtotime($row['data_kvitantsii'].' '.$row['vremya']));
                        $foreignerQr->updated_at = date('Y-m-d H:i:s', strtotime($row['data_kvitantsii'].' '.$row['vremya']));
                        $foreignerQr->document = $foreigner->count() > 0 ? $document : $search;
                        $foreignerQr->transaction = $row['tranzaktsiya'];
                        $foreignerQr->receipt_id = $row['kvitantsiya'];
                        $foreignerQr->sum = $row['zachisleno'];
                        $foreignerQr->sum_from = $row['oplacheno'];
                        $foreignerQr->document = $row['nomer'];
                        $foreignerQr->txn_id = NULL;
                        $foreignerQr->tax_id = NULL;
                        $foreignerQr->save();
                        $totalPaymentsImport++;
                    }

                    if ($foreigner->count() > 0) {
                        $this->line($iterator.' / '.$steps.': '.$search.' — '.$foreigner->surname.' '.$foreigner->name.' '.$foreigner->middle_name.' '.'QRID: '.$foreignerQr->id);
                        $totalPaymentsImportWithIg++;
                    } else {
                        $this->error($iterator.' / '.$steps.': '.$search.' — не найден');
                        $totalPaymentsImportWithoutIg++;
                    }

                    $iterator++;
                }
                $totalPayments++;
            }
        }

        if ($this->confirm('Добавить эти платежи?')) {
            DB::commit();
            $this->info('Всего платежей: '.$totalPayments);
            $this->info('Всего импортировано платежей: '.$totalPaymentsImport);
            $this->info('ИГ найден: '.$totalPaymentsImportWithIg);
            $this->info('Без ИГ: '.$totalPaymentsImportWithoutIg);
        } else {
            DB::rollBack();
        }
    }
}
