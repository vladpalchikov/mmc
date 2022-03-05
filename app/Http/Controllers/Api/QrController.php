<?php

namespace MMC\Http\Controllers\Api;

use MMC\Http\Requests;
use Illuminate\Http\Request;
use MMC\Http\Controllers\Controller;
use MMC\Http\Controllers\Api\ApiController;

use MMC\Models\Foreigner;
use MMC\Models\Tax;
use MMC\Models\ForeignerQR;

use Monolog\Logger;
use Monolog\Handler\RotatingFileHandler;

class QrController extends Controller
{
    use ApiController;

    public function check(Request $request)
    {
        if ($request->has('account')) {
            $log = new Logger('api');
            $log->pushHandler(new RotatingFileHandler(storage_path().'/logs/api/qr.log', 2, Logger::INFO));
            $log->info('-----------------------------');
            foreach ($request->all() as $key => $value) {
                $log->info($key.': '.$value);
            }
            $log->info('-----------------------------');

            if ($request->has('prv_id')) {
                if (Tax::where('provider', $request->input('prv_id'))->count() > 0) {
                    $tax = Tax::where('provider', $request->input('prv_id'))->first();
                }
            }

            if (Foreigner::whereRaw('CONCAT(document_series,document_number)="'.$request->input('account').'"')->count() > 0) {
                $foreigner = Foreigner::whereRaw('CONCAT(document_series,document_number)="'.$request->input('account').'"')->first();
            } else {
                $foreigner = Foreigner::where('document_number', $request->input('account'))->first();
            }

            $txn_id = trim($request->input('txn_id', ''));

            $isNew = false;
            if (ForeignerQR::where('txn_id', $txn_id)->count() > 0) {
                $foreignerQr = ForeignerQR::where('txn_id', $txn_id)->first();
            } else {
                $foreignerQr = new ForeignerQR;
                $foreignerQr->txn_id = $txn_id;
                $isNew = true;
            }

            $foreignerQr->tax_id = isset($tax) ? $tax->id : null;
            $foreignerQr->foreigner_id = isset($foreigner) ? $foreigner->id : null;
            $foreignerQr->status = 1;
            $foreignerQr->document = str_replace(' ', '', $request->input('account'));
            $foreignerQr->inn = trim($request->input('finn', ''));
            $foreignerQr->address = trim($request->input('address', ''));
            $foreignerQr->fio = trim($request->input('fio', ''));
            $foreignerQr->oktmo = trim($request->input('oktmo', ''));
            $foreignerQr->prv_id = trim($request->input('prv_id', ''));
            $foreignerQr->sum = trim($request->input('sum', ''));
            $foreignerQr->sum_from = trim($request->input('sum_from', ''));
            $time = strtotime(str_replace(' ', '+', $request->get('receipt_date')));
            $date = \Carbon\Carbon::createFromTimestamp($time, 'Europe/Samara');
            $foreignerQr->status_datetime = $date;
            $foreignerQr->receipt_id = $request->input('receipt_id', '');
            $foreignerQr->transaction = $request->input('transaction_id', '');

            if (isset($tax)) {
                if ($foreignerQr->sum >= $tax->price) {
                    $foreignerQr->is_verified = true;
                }
            }

            $foreignerQr->save();
            if ($request->has('payqr')) {
                if (isset($foreigner)) {
                    return ['status' => 'Account found', 'foreigner_id' => $foreigner->id, 'foreigner_name' => $foreigner->surname.' '.$foreigner->name.' '.$foreigner->middle_name];
                }
            }

            if ($isNew) {
                return $this->respond(['status' => 'QR created']);
            } else {
                return $this->respond(['status' => 'QR updated']);
            }
        } else {
            return $this->respond(['status' => 'Account missing']);
        }
    }
}
