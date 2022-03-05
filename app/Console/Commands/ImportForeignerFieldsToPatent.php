<?php

namespace MMC\Console\Commands;

use Illuminate\Console\Command;
use \MMC\Models\Foreigner;
use \MMC\Models\ForeignerPatent;

class ImportForeignerFieldsToPatent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mmc:import-foreigner-to-patent';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Добавление полей из ИГ в патенты';

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
        ForeignerPatent::chunk(1000, function($patents) {
            foreach ($patents as $patent) {
                if ($patent->foreigner) {
                    $foreigner = $patent->foreigner;
                    $patent->surname = $foreigner->surname;
                    $patent->name = $foreigner->name;
                    $patent->middle_name = $foreigner->middle_name;
                    $patent->birthday = $foreigner->birthday;
                    $patent->gender = $foreigner->gender;
                    $patent->nationality = $foreigner->nationality;
                    $patent->nationality_line2 = $foreigner->nationality_line2;
                    $patent->document_name = $foreigner->document_name;
                    $patent->document_series = $foreigner->document_series;
                    $patent->document_number = $foreigner->document_number;
                    $patent->document_date = $foreigner->document_date;
                    $patent->address = $foreigner->address;
                    $patent->address_line2 = $foreigner->address_line2;
                    $patent->address_line3 = $foreigner->address_line3;
                    $patent->save();
                }
            }
        });
    }
}
