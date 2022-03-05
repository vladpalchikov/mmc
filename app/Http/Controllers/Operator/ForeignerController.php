<?php

namespace MMC\Http\Controllers\Operator;

use Illuminate\Http\Request;

use MMC\Http\Requests;
use MMC\Http\Controllers\Controller;
use Kris\LaravelFormBuilder\FormBuilder;
use Auth;
use Endroid\QrCode\QrCode;
use MMC\Models\Setting;
use MMC\Models\Foreigner;
use MMC\Models\ForeignerHistory;
use MMC\Models\ForeignerService;
use MMC\Models\Service;
use MMC\Models\ForeignerQR;
use MMC\Models\District;
use MMC\Models\Ifns;
use MMC\Models\MMC;
use MMC\Models\Tax;
use MMC\Models\Client;
use Helper;

use \Carbon\Carbon;
use \GuzzleHttp\Exception\RequestException;

use MMC\Forms\TM\ForeignerForm;

class ForeignerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $foreigners = Foreigner::orderBy('created_at', 'desc');

        if ($request->has('search')) {
            $search = str_replace("\\", "", trim($request->get('search')));
            $foreigners = $foreigners
                ->orWhereRaw('LOWER(surname)="'.mb_strtolower($search).'"')
                ->orWhereRaw('LOWER(name)="'.mb_strtolower($search).'"')
                ->orWhereRaw('LOWER(middle_name)="'.mb_strtolower($search).'"')
                ->orWhereRaw('LOWER(CONCAT(surname," ",name," ",middle_name))="'.mb_strtolower($search).'"')
                ->orWhereRaw('LOWER(CONCAT(surname," ",name))="'.mb_strtolower($search).'"')
                ->orWhereRaw('LOWER(CONCAT(surname," ",middle_name))="'.mb_strtolower($search).'"')
                ->orWhereRaw('LOWER(CONCAT(name," ",middle_name))="'.mb_strtolower($search).'"')
                ->orWhereRaw('CONCAT(document_series,document_number)="'.$search.'"')
                ->orWhere('document_number', '=', $search)
                ->orWhere('inn', '=', $search)
                ->where('status', '<>', 2);
        }

        $foreigners = $foreigners->paginate(50);
        return view('operator.foreigner.index', compact('foreigners'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function my(Request $request)
    {
        $foreignerServices = ForeignerService::where('operator_id', '=', Auth::user()->id)->pluck('foreigner_id')->toArray();
        $foreigners = Foreigner::orderBy('created_at', 'desc')->whereIn('id', $foreignerServices)->paginate(50);
        return view('operator.foreigner.index', compact('foreigners'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(FormBuilder $formBuilder, Request $request)
    {
        $form = $formBuilder->create(ForeignerForm::class, [
            'method' => 'POST',
            'url' => '/operator/foreigners'
        ]);

        return view('operator.foreigner.create', compact('form', 'services'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, FormBuilder $formBuilder)
    {
        $form = $formBuilder->create(ForeignerForm::class);

        if (!$form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        }

        if($request->get('ifns_name') == 0) {
            return redirect()->back()->withErrors(['ifns_error' => 'ИФНС должна быть выбрана'])->withInput();
        }

        $foreigner = new Foreigner;

        if ($request->has('created_at')) {
            $date = date('Y-m-d', strtotime($request->get('created_at')));
        } else {
            $date = date('Y-m-d');
        }

        if ($request->has('created_at_time')) {
            $time = date(' H:i:s', strtotime($request->get('created_at_time')));
        } else {
            $time = date(' H:i:s');
        }

        if (!empty($request->get('client_name'))) {
            $client = new Client;
            $client->name = $request->get('client_name');
            $client->type = $request->get('client_type');
            $client->operator_id = Auth::user()->id;
            $client->is_host_only = true;
            $client->save();
        }


        $foreigner->created_at = $date.$time;
        $foreigner->fill($request->except('created_at'));
        $foreigner->operator_id = Auth::user()->id;
        $foreigner->updated_by = Auth::user()->id;
        $foreigner->is_host_available = $request->has('is_host_available') ? 1 : 0;
        $foreigner->save();

        $foreignerHistory = new ForeignerHistory;
        $foreignerHistory->foreigner_id = $foreigner->id;
        $foreignerHistory->operator_id = Auth::user()->id;
        $foreignerHistory->operator_name = Auth::user()->name;
        $foreignerHistory->fill($foreigner->toArray());
        $foreignerHistory->save();

        return redirect('/operator/foreigners/'.$foreigner->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $foreigner = Foreigner::find($id);

        if (!$foreigner) {
            return view('operator.foreigner.404');
        }

        $clients = [];
        $clients[0] = 'Нет';
        foreach (Client::orderBy('name')->get()->where('is_host_only', '=', '0') as $client) {
            if ($client->type) {
                if ($client->person_document_number) {
                  $clients[$client->id] = $client->name .' ('.$client->person_document_series.$client->person_document_number.')';
                } else {
                  $clients[$client->id] = $client->name;
                }
            } else {
                if ($client->inn) {
                    $clients[$client->id] = $client->name .' ('.$client->inn.')';
                } else {
                    $clients[$client->id] = $client->name;
                }
            }
        }


        if (Auth::user()->hasPermission('tm') || Auth::user()->hasPermission('mu') || Auth::user()->hasPermission('bg')) {
            $services = Service::orderBy('order', 'asc')->where('status', true);
            if (Auth::user()->hasPermission('tm')) {
                $services = $services->where('module', '0');
            }

            if (Auth::user()->hasPermission('mu')) {
                $services = $services->where('module', '1');
            }

            if (Auth::user()->hasPermission('bg')) {
                $services = $services->where('module', '2');
            }
        }


        return view('operator.foreigner.show', compact('foreigner', 'clients', 'services'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, FormBuilder $formBuilder)
    {
        $foreigner = Foreigner::find($id);
        $form = $formBuilder->create(ForeignerForm::class, [
            'method' => 'PUT',
            'url' => '/operator/foreigners/'.$foreigner->id,
            'model' => $foreigner
        ]);

        $services = [];
        foreach (Service::orderBy('order', 'asc')->where('module', '=', '0')->where('status', '=', true)->get() as $service) {
            $services[$service->id] = $service;
        }

        return view('operator.foreigner.create', compact('form', 'foreigner', 'services'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id, FormBuilder $formBuilder)
    {
        $foreigner = Foreigner::find($id);

        $form = $formBuilder->create(ForeignerForm::class, [
            'model' => $foreigner
        ]);

        if (!$form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        }

        if($request->get('ifns_name') == 0) {
            return redirect()->back()->withErrors(['ifns_error' => 'ИФНС должна быть выбрана'])->withInput();
        }

        if (!empty($request->get('client_name'))) {
            $client = new Client;
            $client->name = $request->get('client_name');
            $client->type = $request->get('client_type');
            $client->operator_id = Auth::user()->id;
            $client->is_host_only = true;
            $client->save();
        }

        $foreigner->fill($request->except('created_at'));
        $foreigner->updated_by = Auth::user()->id;
        $foreigner->is_host_available = $request->has('is_host_available') ? 1 : 0;
        $foreigner->save();

        if ($request->has('services')) {
            foreach ($request->get('services') as $service_id) {
                $service = Service::find($service_id);
                $foreignerService = new ForeignerService;
                $foreignerService->foreigner_id = $foreigner->id;
                $foreignerService->operator_id = Auth::user()->id;
                $foreignerService->service_id = $service->id;
                $foreignerService->service_description = $service->description;
                $foreignerService->service_name = $service->name;
                $foreignerService->service_price = $service->price;
                $foreignerService->is_complex = $service->is_complex;
                $foreignerService->service_agent_compensation = $service->agent_compensation;
                $foreignerService->service_principal_sum = $service->principal_sum;
                $foreignerService->service_order = $service->order;
                $foreignerService->client_id = $request->get('client_id');
                $foreignerService->payment_method = $request->get('payment_method');
                $foreignerService->save();

                if ($service->tax_id) {
                    $foreignerQr = new ForeignerQR;
                    $foreignerQr->tax_id = $service->tax_id;
                    $foreignerQr->foreigner_id = $foreigner->id;
                    $foreignerQr->operator_id = Auth::user()->id;
                    $foreignerQr->status = 0;
                    $foreignerQr->status_datetime = date('Y-m-d H:i:s');
                    // $foreignerQr->save();
                }
            }
        }

        $foreignerHistory = new ForeignerHistory;
        $foreignerHistory->foreigner_id = $foreigner->id;
        $foreignerHistory->operator_id = Auth::user()->id;
        $foreignerHistory->operator_name = Auth::user()->name;
        $foreignerHistory->fill($foreigner->toArray());
        $foreignerHistory->save();

        return redirect('/operator/foreigners/'.$foreigner->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Foreigner::find($id)->delete();
    }

    public function storeServices($foreigner_id, Request $request)
    {
        $foreigner = Foreigner::find($foreigner_id);
        if ($request->has('services')) {
            foreach ($request->get('services') as $service_id) {
                $service = Service::find($service_id);
                $foreignerService = new ForeignerService;
                $foreignerService->foreigner_id = $foreigner->id;
                $foreignerService->operator_id = Auth::user()->id;
                $foreignerService->service_id = $service->id;
                $foreignerService->service_description = $service->description;
                $foreignerService->service_name = $service->name;
                $foreignerService->service_price = $service->price;
                $foreignerService->is_complex = $service->is_complex;
                $foreignerService->service_agent_compensation = $service->agent_compensation;
                $foreignerService->service_principal_sum = $service->principal_sum;
                $foreignerService->service_order = $service->order;
                if ($request->has('is_host_match')) {
                    $foreignerService->client_id = $foreigner->host_id;
                } else {
                    if ($request->has('client_id')) {
                        $foreignerService->client_id = $request->get('client_id');
                    }
                }
                $foreignerService->payment_method = $request->get('payment_method');

                $taxPrice = 0;
                if ($service->tax_id) {
                    if (Tax::find($service->tax_id)) {
                        $tax = Tax::find($service->tax_id);
                        $taxIdentify = $tax->code;
                        $taxPrice = $tax->price;
                    }
                }

                if (Ifns::find($foreigner->ifns_name)) {
                    $ifns = Ifns::find($foreigner->ifns_name);
                } else {
                    $ifns = new \stdClass;
                    $ifns->kod = '';
                    $ifns->inn = '';
                }

                $uin = 0;
                $uin_request_allow = false;
                if ($uin_request_allow == true) {
                if (!$foreigner->inn || $foreigner->inn == 0) {
                    $client = new \GuzzleHttp\Client();
                    try {
                        $response = $client->request('GET', 'https://murmuring-bayou-68144.herokuapp.com/get_uin/', [
                            'auth' => ['client1', 'qC$svg7'],
                            'query' => [
                                'sum' => $taxPrice,
                                'address' => isset($foreigner->address) ? $foreigner->address : ' ',
                                'oktmo' => isset($foreigner->oktmo) ? $foreigner->oktmo : ' ',
                                'ifnsinn' => !empty($ifns->inn) ? $ifns->inn : ' ',
                                'fio' => $foreigner->surname.' '.$foreigner->name.' '.$foreigner->middle_name
                            ]
                        ]);
                    } catch (RequestException $e) {

                    }

                    if (isset($response)) {
                        $data = json_decode($response->getBody()->read(1024));
                        $uin = $data->data->uin;
                    }
                }}

                $foreignerService->uin = $uin;
                $foreignerService->save();

                if ($service->tax_id) {
                    $foreignerQr = new ForeignerQR;
                    $foreignerQr->tax_id = $service->tax_id;
                    $foreignerQr->foreigner_id = $foreigner->id;
                    $foreignerQr->operator_id = Auth::user()->id;
                    $foreignerQr->status = 0;
                    $foreignerQr->status_datetime = date('Y-m-d H:i:s');
                    // $foreignerQr->save();
                }
            }
        }

        return redirect('/operator/foreigners/'.$foreigner_id);
    }

    /**
     * Set payed status for service
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function servicePay($service_id)
    {
        \Log::info('------------{'.$service_id.'}------------');
        $service = ForeignerService::findOrFail($service_id);
        \Log::info($service->service_name);
        if ($service->payment_status == 0) {
            $service->payment_status = 1;
            $service->cashier_id = Auth::user()->id;
            $service->payment_at = Carbon::now();
            \Log::info($service->id.' - Оплата подтверждена');
        } else {
            $service->payment_status = 0;
            $service->cashier_id = null;
            $service->payment_at = null;
            \Log::info($service->id.' - Оплата отменена');
        }
        $service->save();
        \Log::info($service->id.' - Заявка сохранена');
        return redirect()->back();
    }

    /**
     * Set payed status for service
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function allServicesPay($foreigner_id)
    {
        $foreigner = Foreigner::find($foreigner_id);

        foreach ($foreigner->services as $service) {
            if ($service->payment_status == 0) {
                $service->payment_status = 1;
                $service->cashier_id = Auth::user()->id;
            }
            $service->save();
        }
        return redirect('/operator/foreigners/'.$foreigner->id);
    }

    /**
     * Set closed status
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function close($id)
    {
        $foreigner = Foreigner::find($id);
        $foreigner->status = 2;
        $foreigner->save();
        return redirect('/operator/foreigners/');
    }

    /**
     * Get QR code
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function qrcode($identification, $tax_id, $foreignerQr = null, $service_id = null)
    {
        if ($service_id) {
            $foreignerService = ForeignerService::find($service_id);
        }

        if (!Foreigner::find($identification)) {
            if (Foreigner::whereRaw('CONCAT(document_series,document_number)="'.$identification.'"')->count() > 0) {
                $foreigner = Foreigner::whereRaw('CONCAT(document_series,document_number)="'.$identification.'"')->orderBy('id', 'desc')->first();
            } else {
                return false;
            }
        } else {
            $foreigner = Foreigner::find($identification);
        }

        $taxIdentify = '';
        $taxPrice = 0;
        if (Tax::find($tax_id)) {
            $tax = Tax::find($tax_id);
            $taxIdentify = $tax->code;
            $taxPrice = $tax->price;
        }

        if (Ifns::find($foreigner->ifns_name)) {
            $ifns = Ifns::find($foreigner->ifns_name);
        } else {
            $ifns = new \stdClass;
            $ifns->kod = '';
            $ifns->inn = '';
        }

        if ($foreigner->user) {
            if (MMC::find($foreigner->user->mmc_id)) {
                $mmc = MMC::find($foreigner->user->mmc_id);
            } else {
                $mmc = MMC::first();
            }
        } else {
            $mmc = MMC::first();
        }

        $uin = 0;
        $uin_request_allow = false;
        if ($uin_request_allow == true) {
        if (!$foreigner->inn || $foreigner->inn == 0) {
            $client = new \GuzzleHttp\Client();
            try {
                $response = $client->request('GET', 'https://murmuring-bayou-68144.herokuapp.com/get_uin/', [
                    'auth' => ['client1', 'qC$svg7'],
                    'query' => [
                        'sum' => $taxPrice,
                        'address' => $foreigner->address,
                        'oktmo' => $foreigner->oktmo,
                        'ifnsinn' => $ifns->inn,
                        'fio' => $foreigner->surname.' '.$foreigner->name.' '.$foreigner->middle_name
                    ]
                ]);
            } catch (RequestException $e) {

            }

            if (isset($response)) {
                $data = json_decode($response->getBody()->read(1024));
                $uin = $data->data->uin;
            }
        }}

        if ($taxIdentify == "МЦПА") {
            $qrString = mb_strtoupper($taxIdentify.$mmc->city_code.'$'.$foreigner->surname.'$'.$foreigner->name.'$'.$foreigner->middle_name.'$'.$foreigner->document_series.'$'.$foreigner->document_number.'$'.$foreigner->address.' '.$foreigner->address_line2.' '.$foreigner->address_line3.'$'.$foreigner->oktmo.'$'.$ifns->kod.'$'.$ifns->inn.'$'.$taxPrice.'$'.$foreigner->inn);
        } else {
            $qrString = mb_strtoupper($taxIdentify.$mmc->city_code.'$'.$foreigner->surname.'$'.$foreigner->name.'$'.$foreigner->middle_name.'$'.$foreigner->document_series.'$'.$foreigner->document_number.'$'.$foreigner->address.' '.$foreigner->address_line2.' '.$foreigner->address_line3.'$36701000$'.$ifns->kod.'$'.$ifns->inn.'$'.$taxPrice.'$'.$foreigner->inn);
        }

        header('Content-type: image/png');
        $qrCode = new QrCode();
        $qrCode->setText($qrString)->setSize(200)->setImageType(QrCode::IMAGE_TYPE_PNG)->render();
    }

    /**
     * Print document for service
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function servicePrint($service_id)
    {
        $service = ForeignerService::findOrFail($service_id);
        $foreigner = Foreigner::findOrFail($service->foreigner_id);

        if ($service->payment_status == 0 || $service->foreigner_name == null || $service->foreigner_address == null) {
            $service->foreigner_surname = $foreigner->surname;
            $service->foreigner_name = $foreigner->name;
            $service->foreigner_middle_name = $foreigner->middle_name;
            $service->foreigner_birthday = $foreigner->birthday;
            $service->foreigner_gender = $foreigner->gender;
            $service->foreigner_nationality = $foreigner->nationality;
            $service->foreigner_nationality_line2 = $foreigner->nationality_line2;
            $service->foreigner_document_name = $foreigner->document_name;
            $service->foreigner_document_series = $foreigner->document_series;
            $service->foreigner_document_number = $foreigner->document_number;
            $service->foreigner_document_date = $foreigner->document_date;
            $service->foreigner_document_issuedby = $foreigner->document_issuedby;
            $service->foreigner_address = $foreigner->address;
            $service->foreigner_address_line2 = $foreigner->address_line2;
            $service->foreigner_address_line3 = $foreigner->address_line3;
            $service->foreigner_registration_date = $foreigner->registration_date;
            $service->foreigner_phone = $foreigner->phone;
            $service->foreigner_oktmo = $foreigner->oktmo;
            $service->foreigner_ifns_name = $foreigner->ifns_name;
            $service->operator_id = Auth::user()->id;
            $service->save();
        }

        if (Tax::find($service->service->tax_id)) {
            $tax = Tax::find($service->service->tax_id);
        } else {
            $tax = null;
        }

        if ($foreigner->user) {
            if (MMC::find($foreigner->user->mmc_id)) {
                $mmc = MMC::find($foreigner->user->mmc_id);
            } else {
                $mmc = MMC::first();
            }
        } else {
            $mmc = MMC::first();
        }

        return view('operator.foreigner.service_print', compact('service', 'foreigner', 'tax', 'mmc'));
    }

    /**
     * Delete service
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function serviceDelete($service_id)
    {
        $service = ForeignerService::findOrFail($service_id);
        $foreigner = Foreigner::findOrFail($service->foreigner_id);

        if (Auth::user()->hasRole('administrator|managertm|managertmsn|business.manager|business.managerbg|managerbg') && $service->payment_status == 0 && date('Y.m.d', strtotime($service->created_at)) == date('Y.m.d')) {
            $service->delete();
        }

        return redirect('/operator/foreigners/'.$foreigner->id);
    }

    public function qrSave($foreigner_id, $tax_id)
    {
        $foreignerQr = new ForeignerQR;
        $foreignerQr->tax_id = $tax_id;
        $foreignerQr->foreigner_id = $foreigner_id;
        $foreignerQr->operator_id = Auth::user()->id;
        $foreignerQr->status_datetime = date('Y-m-d H:i:s');
        $foreignerQr->status = 0;
        // $foreignerQr->save();

        return redirect('/operator/foreigners/'.$foreigner_id.'/qrprint/'.$tax_id);
    }

    public function qrPrint($foreigner_id, $tax_id, $foreignerQr = null)
    {
        $foreigner = Foreigner::findOrFail($foreigner_id);
        if (Tax::find($tax_id)) {
            $tax = Tax::find($tax_id);
        } else {
            $tax = null;
        }

        if ($foreigner->user) {
            if (MMC::find($foreigner->user->mmc_id)) {
                $mmc = MMC::find($foreigner->user->mmc_id);
            } else {
                $mmc = MMC::first();
            }
        } else {
            $mmc = MMC::first();
        }

        // $foreignerQr = ForeignerQR::find($foreignerQr);

        return view('operator.foreigner.qr_print', compact('foreigner', 'tax', 'mmc'));
    }

    public function qrReturn($foreigner_id, $foreignerQr)
    {
        $qr = ForeignerQR::find($foreignerQr);
        $client = new \GuzzleHttp\Client();

        $response = $client->request('GET', 'http://api.odnokasanie.ru/mc_cancel_payment/txn_id/'.$qr->txn_id, [
            'auth' => ['mc', '2fR$s_14vG7k']
        ]);

        $result = (integer) $response->getBody()->read(1024);

        if ($result) {
            $qr->status = 2;
            $qr->save();
            return [
                'status' => true
            ];
        } else {
            return [
                'status' => false
            ];
        }
    }

    public function qrReturnPrint($foreigner_id, $foreignerQr)
    {
        $foreigner = Foreigner::findOrFail($foreigner_id);
        $qr = ForeignerQR::find($foreignerQr);
        return view('operator.foreigner.qr_return_print', compact('qr', 'foreigner'));
    }

    /**
     * Get OKTMO from address
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getOktmo(Request $request)
    {
        $data = [];
        $data['oktmo'] = '';
        $data['ifns'] = 'Для указанного адреса ИФНС не найден';
        $data['address'] = 'Не определен';
        $data['district'] = 'Не определен';
        $data['inn'] = 'Не определен';
        $data['ifns_name'] = 'Не определен';
        if ($request->has('address')) {
            $address = $request->get('address');
        } else {
            return response()->json($data);
        }

        $api = new \Yandex\Geo\Api();
        $api->setQuery($address);

        $api->setLimit(1)->setKind('district')->load();
        $addressInfo = $api->getResponse()->getFirst();

        if ($addressInfo) {
            $locality = $addressInfo->getLocalityName();
            if ($locality == 'Самара') {
                $api->setPoint($addressInfo->getLongitude(), $addressInfo->getLatitude());
                $api->setLimit(1)->setKind('district')->load();
                $districtInfo = $api->getResponse()->getFirst();
                if (isset($districtInfo)) {
                    $data['district'] = $districtInfo->getDependentLocalityName();
                } else {
                    $data['district'] = 'Не определен';
                }
            } elseif ($locality == 'Тольятти') {
                $data['district'] = $locality;
                if (stristr(mb_strtolower($address), 'автозаводский')) {
                    $data['district'] = $locality.' (Автозаводский район)';
                }

                if (stristr(mb_strtolower($address), 'комсомольский')) {
                    $data['district'] = $locality.' (Комсомольский район)';
                }

                if (stristr(mb_strtolower($address), 'центральный')) {
                    $data['district'] = $locality.' (Центральный район)';
                }
            } else {
                $data['district'] = $locality;
            }

            if (!$data['district']) {
                $data['district'] = 'Не определен';
            }

            $data['locality'] = $addressInfo->getLocalityName();
            $data['address'] = $addressInfo->getAddress();
        }

        if (District::where('district', '=', $data['district'])->count() > 0) {
            $data['oktmo'] = District::where('district', '=', $data['district'])->first()->oktmo;
            $data['ifns'] = District::where('district', '=', $data['district'])->first()->ifns;
            if (Ifns::where('kod', '=', $data['ifns'])->count() > 0) {
                $data['ifns'] = Ifns::where('kod', '=', $data['ifns'])->first()->id;
            }
        }

        if (!$data['district']) {
            $data['district'] = 'Не определен';
        }
        return response()->json($data);
    }

    public function getInn(Request $request, \MMC\Library\INN $inn)
    {
        if (!$request->has('captcha')) {
            $response = $inn->getCaptcha();
            if ($response['status']) {
                Setting::saveSetting('inn_lock', 'true');
                return response()->json(['captchaToken' => $response['captchaToken']]);
            } else {
                Setting::saveSetting('inn_lock', 'false');
                return response()->json(['captchaToken' => $response['captchaToken'], 'error' => $response['error']]);
            }
        } else {
            $foreignerData = [];
            $foreignerData['surname'] = $request->get('surname');
            $foreignerData['name'] = $request->get('name');
            $foreignerData['middle_name'] = $request->get('middle_name');
            $foreignerData['birthday'] = $request->get('birthday');
            $foreignerData['document'] = $request->get('document');
            $foreignerData['document_date'] = $request->get('document_date');
            $response = $inn->getInn($foreignerData, $request->get('captchaToken'), $request->get('captcha'));
            if ($response['status']) {
                Setting::saveSetting('inn_lock', 'false');
                Setting::saveSetting('inn_success', date('Y-m-d H:i:s'));
                return response()->json(['inn' => $response['inn']]);
            } else {
                Setting::saveSetting('inn_lock', 'false');
                return response()->json(['error' => $response['error'], 'inn' => $response['inn']]);
            }
        }
    }

    /**
     * Repayment print and status
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function repayment($service_id, Request $request)
    {
        $service = ForeignerService::findOrFail($service_id);
        $foreigner = Foreigner::findOrFail($service->foreigner_id);

        if ($service->repayment_status == 0) {
            $service->repayment_status = 1;
            $service->save();

            return view('operator.foreigner.service_repayment_print', compact('service', 'foreigner'));
        } elseif ($service->repayment_status == 1) {
            if ($request->has('status')) {
                $service->repayment_status = 3;
                $service->save();

                return redirect('/operator/foreigners/'.$foreigner->id);
            } else {
                $service->repayment_status = 0;
                $service->save();
                return redirect('/operator/foreigners/'.$foreigner->id);
            }
        }
    }

    /**
     * Repayment print
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function repaymentPrint($service_id, Request $request)
    {
        $service = ForeignerService::findOrFail($service_id);
        $foreigner = Foreigner::findOrFail($service->foreigner_id);
        return view('operator.foreigner.service_repayment_print', compact('service', 'foreigner'));
    }

    /**
     * Find client by document
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function findclient(Request $request)
    {
        $document_series = '';
        if ($request->has('document_number')) {
            $document_number = $request->get('document_number');
            $search = Foreigner::where('document_number', '=', $document_number);

            if ($request->has('document_series')) {
                $document_series = $request->get('document_series');
                $search = $search->where('document_series', '=', $document_series);
            }

            if ($search->count() > 1) {
                $foreigners = $search->get();
                return response()->json([
                    'count' => $search->count(),
                    'foreigners' => $foreigners->toArray()
                ]);
            } elseif ($search->count() == 1) {
                $foreigner = $search->first();
                return response()->json([
                    'count' => 1,
                    'redirect' => '/operator/foreigners/'.$foreigner->id.'/edit'
                ]);
            } else {
                return response()->json([
                    'count' => 0,
                    'redirect' => '/operator/foreigners/create?document_series='.$document_series.'&document_number='.$document_number
                ]);
            }
        } else {
            return response()->json([
                'count' => 0,
                'redirect' => '/operator/foreigners/create'
            ]);
        }
    }
}
