<?php

namespace MMC\Console\Commands;

use Illuminate\Console\Command;
use MMC\Models\Foreigner;
use MMC\Models\ForeignerPatent;
use MMC\Library\ArrayToXml;
use MMC\Library\XmlDigitalSignature;
use Storage;

class CreateXmlForPatents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mmc:xml-patents';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Создание XML файлов для патентов';

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
        $patents = ForeignerPatent::take(10)->inRandomOrder()->with('foreigner')->get()->toArray();
        // $patents = ForeignerPatent::whereRaw('Date(created_at) = CURDATE()')->with('foreigner')->get()->toArray();
        foreach ($patents as $patent) {
            $patent_id = $patent['id'];
            unset($patent['id']);
            unset($patent['foreigner_id']);

            $dsig = new XmlDigitalSignature();
            $dsig->loadPrivateKey(public_path().'/xml.pem', 'secret');
            $dsig->loadPublicKey(public_path().'/xml_public.pem');

            foreach ($patent as $key => $value) {
                if (is_string($value) && !empty($value)) {
                    $dsig->addObject($value, $key);
                }

                if (is_array($value)) {
                    foreach ($value as $foreigner_key => $foreigner_value) {
                        if (is_string($foreigner_value) && !empty($foreigner_value)) {
                            $dsig->addObject($foreigner_value, 'foreigner_'.$foreigner_key);
                        }
                    }
                }
            }

            $dsig->sign();

            $result = $dsig->getSignedDocument();
            Storage::disk('patent_xml')->put($patent_id.'.xml', $result);
        }
    }
}
