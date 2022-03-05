<?php

namespace MMC\Http\Controllers\Operator;

use Illuminate\Http\Request;

use MMC\Http\Requests;
use MMC\Http\Controllers\Controller;
use Kris\LaravelFormBuilder\FormBuilder;
use Auth;
use Endroid\QrCode\QrCode;
use MMC\Models\Client;
use MMC\Models\Foreigner;
use MMC\Models\ForeignerService;
use MMC\Models\ForeignerServiceGroup;
use MMC\Models\User;
use MMC\Models\Service;
use MMC\Models\MUApplication;
use MMC\Models\MMC;
use MMC\Models\UserMMC;

class MUServiceController extends Controller
{
	 /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $groups = ForeignerServiceGroup::orderBy('created_at', 'desc');

        $mmcList = MMC::whereIn('id', Auth::user()->mmcListId())->get();

        if ($request->has('search')) {
            $search = trim($request->get('search'));
            if (Client::whereRaw('LOWER(name) LIKE "%'.mb_strtolower($search).'%"')->count() > 0) {
                $client = Client::whereRaw('LOWER(name) LIKE "%'.mb_strtolower($search).'%"')->first()->id;
                $groups = $groups->where('client_id', '=', $client);
            }

            if (is_numeric($search)) {
                $groups = $groups->where('id', '=', $search);
            }
        }

        if (Auth::user()->hasRole('administrator|chief.accountant')) {
            if ($request->has('mmc')) {
                $mmc_id = $request->get('mmc');
            } else {
                $mmc_id = Auth::user()->mmcListId()[0];
            }
        }

        $operators = [];
        $operator = User::where('active', '=', 1)->orderBy('name', 'asc');

        if ($request->has('mmc')) {
            $mmcUsers = UserMMC::where('mmc_id', $request->get('mmc'))->pluck('user_id')->toArray();
            $operator = $operator->whereIn('id', $mmcUsers);
        } else {
            $mmcUsers = UserMMC::whereIn('mmc_id', Auth::user()->mmcListId())->pluck('user_id')->toArray();
            $operator = $operator->whereIn('id', $mmcUsers);
        }

        if (Auth::user()->hasRole('business.manager|accountant|managermusn|managermu|cashier')) {
            $mmcUsers = UserMMC::whereIn('mmc_id', Auth::user()->mmcListId())->pluck('user_id')->toArray();
            $operator = $operator->whereIn('id', $mmcUsers);
        }

        foreach ($operator->get() as $user) {
            if ($user->hasRole('managertmsn|managertm')) {
                $operators[] = $user;
            }
        }

        if (Auth::user()->hasRole('managermu')) {
            $groups = $groups->where('operator_id', '=', Auth::user()->id);
        }

        if ($request->has('manager')) {
            $groups = $groups->where('operator_id', '=', $request->get('manager'));
        }

        $groups = $groups->paginate(50);
        return view('operator.muservices.index', compact('groups', 'operators', 'mmcList'));
    }

	/**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(FormBuilder $formBuilder, Request $request)
    {
        $client_id = null;
        if ($request->has('client_id')) {
            $client_id = $request->get('client_id');
        }

        $form = $formBuilder->create(\MMC\Forms\MU\MUServiceForm::class, [
            'method' => 'POST',
            'data' => [
                'client_id' => $client_id
            ],
            'url' => '/operator/muservices'
        ]);

        $services = [];
        foreach (Service::orderBy('order', 'asc')->where('status', '=', true)->where('module', '=', 1)->get() as $service) {
            $services[$service->id] = $service;
        }

        $countServices = 1;

        return view('operator.muservices.create', compact('form', 'countServices', 'services', 'client_id'));
    }

    public function foreignerInfo(Request $request)
    {
        if ($request->has('document_number')) {
            $foreigner = Foreigner::where('document_series', $request->get('document_series'))->where('document_number', $request->get('document_number'));
            if ($foreigner->count() > 0) {
                return response()->json($foreigner->first()->toArray());
            }
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, FormBuilder $formBuilder)
    {
        $form = $formBuilder->create(\MMC\Forms\MU\MUServiceForm::class);

        if (!$form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        }

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

        $service_id = $request->get('service');
        $service = Service::find($service_id);

        $group = new ForeignerServiceGroup;
        $group->client_id = $request->get('client_id');
        $group->service_id = $service_id;
        $group->operator_id = Auth::user()->id;
        $group->payment_method = $request->get('payment_method');
        $group->service_count = count($request->get('foreigner'));
        $group->service_name = $service->name;
        $group->service_price = $service->price;
        $group->is_complex = $service->is_complex;
        $group->service_description = $service->description;
        $group->save();

        $foreignerCount = 0;
        foreach ($request->get('foreigner') as $foreignerData) {
            if (!empty($foreignerData['surname'])) {
                $foreigner = Foreigner::where('document_series', $foreignerData['document_series'])->where('document_number', $foreignerData['document_number']);

                if ($foreigner->count() > 0) {
                    $foreigner = $foreigner->first();
                } else {
                    $foreigner = new Foreigner;
                    $foreigner->document_name = $foreignerData['document_name'];
                    $foreigner->document_series = $foreignerData['document_series'];
                    $foreigner->document_number = $foreignerData['document_number'];
                    $foreigner->surname = $foreignerData['surname'];
                    $foreigner->name = $foreignerData['name'];
                    $foreigner->middle_name = empty($foreignerData['middle_name']) ? null : $foreignerData['middle_name'];
                    $foreigner->birthday = $foreignerData['birthday'];
                    $foreigner->nationality = $foreignerData['nationality'];
                    $foreigner->operator_id = Auth::user()->id;
                    $foreigner->save();
                }

                $foreignerCount++;

                $muService = new ForeignerService;
                $muService->created_at = $date.$time;
                $muService->foreigner_id = $foreigner->id;
                $muService->client_id = $request->get('client_id');
                $muService->payment_method = $request->get('payment_method');
                $muService->type_appeal = $foreignerData['type_appeal'];
                $muService->operator_id = Auth::user()->id;
                $muService->updated_by = Auth::user()->id;
                $muService->service_id = $service->id;
                $muService->service_description = $service->description;
                $muService->service_name = $service->name;
                $muService->service_price = $service->price;
                $muService->is_complex = $service->is_complex;
                $muService->service_order = $service->order;
                $muService->is_mu = true;
                $muService->group_id = $group->id;
                $muService->save();
            }
        }

        $group->service_count = $foreignerCount;
        $group->save();

        return redirect('/operator/clients/'.$muService->client_id);
    }

    public function edit($id, FormBuilder $formBuilder, Request $request)
    {
        $group = ForeignerServiceGroup::find($id);
        $client_id = null;
        if ($request->has('client_id')) {
            $client_id = $request->get('client_id');
        }

        $form = $formBuilder->create(\MMC\Forms\MU\MUServiceForm::class, [
            'method' => 'PUT',
            'model' => $group,
            'data' => [
                'client_id' => $client_id
            ],
            'url' => '/operator/muservices/'.$group->id
        ]);

        $services = [];
        foreach (Service::orderBy('order', 'asc')->where('status', '=', true)->where('module', '=', 1)->get() as $service) {
            $services[$service->id] = $service;
        }

        $countServices = 1;

        return view('operator.muservices.edit', compact('group', 'form', 'countServices', 'services', 'client_id'));
    }

    public function update($id, Request $request, FormBuilder $formBuilder)
    {
        $form = $formBuilder->create(\MMC\Forms\MU\MUServiceForm::class, [
            'model' => ForeignerServiceGroup::find($id)
        ]);

        if (!$form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        }

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

        $service_id = $request->get('service');
        $service = Service::find($service_id);

        $group = ForeignerServiceGroup::find($id);
        $group->client_id = $request->get('client_id');
        $group->service_id = $service_id;
        $group->operator_id = Auth::user()->id;
        $group->payment_method = $request->get('payment_method');
        $group->service_count = count($request->get('foreigner')) + count($request->get('old_foreigner'));
        $group->service_name = $service->name;
        $group->service_price = $service->price;
        $group->is_complex = $service->is_complex;
        $group->service_description = $service->description;
        $group->save();

        $foreignerOldCount = 0;
        if ($request->has('old_foreigner')) {
            foreach ($request->get('old_foreigner') as $foreignerData) {
                if (!empty($foreignerData['surname'])) {
                    $foreigner = Foreigner::where('document_series', $foreignerData['document_series'])->where('document_number', $foreignerData['document_number']);

                    if ($foreigner->count() > 0) {
                        $foreigner = $foreigner->first();

                        $foreigner->document_name = $foreignerData['document_name'];
                        $foreigner->document_series = $foreignerData['document_series'];
                        $foreigner->document_number = $foreignerData['document_number'];
                        $foreigner->surname = $foreignerData['surname'];
                        $foreigner->name = $foreignerData['name'];
                        $foreigner->middle_name = empty($foreignerData['middle_name']) ? null : $foreignerData['middle_name'];
                        $foreigner->birthday = $foreignerData['birthday'];
                        $foreigner->nationality = $foreignerData['nationality'];
                        $foreigner->save();

                        $foreignerOldCount++;

                        $muService = ForeignerService::find($foreignerData['service_id']);
                        $muService->client_id = $request->get('client_id');
                        $muService->payment_method = $request->get('payment_method');
                        $muService->type_appeal = $foreignerData['type_appeal'];
                        $muService->updated_by = Auth::user()->id;
                        $muService->service_id = $service->id;
                        $muService->service_description = $service->description;
                        $muService->service_name = $service->name;
                        $muService->service_price = $service->price;
                        $muService->is_complex = $service->is_complex;
                        $muService->service_order = $service->order;
                        $muService->is_mu = true;
                        $muService->group_id = $group->id;
                        $muService->save();
                    }
                }
            }
        }

        $foreignerCount = 0;
        if ($request->has('foreigner')) {
            foreach ($request->get('foreigner') as $foreignerData) {
                if (!empty($foreignerData['surname'])) {
                    $foreigner = Foreigner::where('document_series', $foreignerData['document_series'])->where('document_number', $foreignerData['document_number']);

                    if ($foreigner->count() > 0) {
                        $foreigner = $foreigner->first();
                    } else {
                        $foreigner = new Foreigner;
                        $foreigner->document_name = $foreignerData['document_name'];
                        $foreigner->document_series = $foreignerData['document_series'];
                        $foreigner->document_number = $foreignerData['document_number'];
                        $foreigner->surname = $foreignerData['surname'];
                        $foreigner->name = $foreignerData['name'];
                        $foreigner->middle_name = empty($foreignerData['middle_name']) ? null : $foreignerData['middle_name'];
                        $foreigner->birthday = $foreignerData['birthday'];
                        $foreigner->nationality = $foreignerData['nationality'];
                        $foreigner->save();
                    }

                    $foreignerCount++;

                    $muService = new ForeignerService;
                    $muService->created_at = $date.$time;
                    $muService->foreigner_id = $foreigner->id;
                    $muService->client_id = $request->get('client_id');
                    $muService->payment_method = $request->get('payment_method');
                    $muService->type_appeal = $foreignerData['type_appeal'];
                    $muService->operator_id = Auth::user()->id;
                    $muService->updated_by = Auth::user()->id;
                    $muService->operator_id = Auth::user()->id;
                    $muService->service_id = $service->id;
                    $muService->service_description = $service->description;
                    $muService->service_name = $service->name;
                    $muService->service_price = $service->price;
                    $muService->is_complex = $service->is_complex;
                    $muService->service_order = $service->order;
                    $muService->is_mu = true;
                    $muService->group_id = $group->id;
                    $muService->save();
                }
            }
        }

        $group->service_count = $foreignerCount + $foreignerOldCount;
        $group->save();

        return redirect('/operator/clients/'.$request->get('client_id'));
    }

    /**
     * Set payed status for service
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function servicePay($group_id)
    {
        $group = ForeignerServiceGroup::find($group_id);
        if ($group->payment_status == 0) {
            $group->payment_status = 1;
            $group->cashier_id = Auth::user()->id;
            $group->payment_at = \Carbon\Carbon::now();
        } else {
            $group->payment_status = 0;
            $group->cashier_id = null;
            $group->payment_at = null;
        }
        $group->save();

        foreach ($group->services as $service) {
            if ($service->payment_status == 0) {
                $service->payment_status = 1;
                $service->cashier_id = Auth::user()->id;
                $service->payment_at = \Carbon\Carbon::now();
            } else {
                $service->payment_status = 0;
                $service->cashier_id = null;
                $service->payment_at = null;
            }
            $service->save();
        }

        return redirect('/operator/clients/'.$group->client_id);
    }

    /**
     * Print document for service
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function servicePrint($group_id)
    {
        $group = ForeignerServiceGroup::findOrFail($group_id);
        return view('operator.muservices.service_print', compact('group'));
    }

    public function destroy($id)
    {
        $group = ForeignerServiceGroup::findOrFail($id);
        $group->services()->delete();
        $group->delete();
    }
}