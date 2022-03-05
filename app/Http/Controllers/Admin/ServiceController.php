<?php

namespace MMC\Http\Controllers\Admin;

use Illuminate\Http\Request;

use MMC\Http\Requests;
use MMC\Http\Controllers\Controller;
use Kris\LaravelFormBuilder\FormBuilder;
use MMC\Models\Company;
use MMC\Models\Service;
use MMC\Models\ServiceComplex;
use Auth;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $services = Service::orderBy('order', 'asc');

        if (Auth::user()->hasPermission('tm') || Auth::user()->hasPermission('mu') || Auth::user()->hasPermission('bg')) {
            $modules = [];
            if (Auth::user()->hasPermission('tm')) {
                $modules[] = 0;
            }

            if (Auth::user()->hasPermission('mu')) {
                $modules[] = 1;
            }

            if (Auth::user()->hasPermission('bg')) {
                $modules[] = 2;
            }

            $services = $services->whereIn('module', $modules);
        }

        if ($request->has('active')) {
            $services = $services->where('status', '=', '0');
        } else {
            $services = $services->where('status', '=', '1');
        }

        if ($request->has('company')) {
            $services = $services->where('company_id', $request->get('company'));
        }

        if ($request->has('module')) {
            $services = $services->where('module', $request->get('module'));
        }

        $services = $services->get();

        $companies = Company::all();

        return view('admin.service.index', compact('services', 'companies'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(FormBuilder $formBuilder)
    {
        $form = $formBuilder->create(\MMC\Forms\TM\ServiceForm::class, [
            'method' => 'POST',
            'url' => '/services'
        ]);
        $companies = \MMC\Models\Company::orderBy('id', 'desc')->get();
        $service = new Service;
        return view('admin.service.create', compact('form', 'companies', 'service'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, FormBuilder $formBuilder)
    {
        $form = $formBuilder->create(\MMC\Forms\TM\ServiceForm::class);

        if (!$form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        }

        $service = new Service;
        $service->fill($request->all());
        $service->status = $request->has('status') ? 1 : 0;
        $service->is_complex = $request->has('is_complex') ? 1 : 0;
        $service->labor_exchange = $request->has('labor_exchange') ? 1 : 0;
        $service->save();

        foreach ($request->complex as $company_id => $price) {
            if (!$price) {
                $price = 0;
            }

            if (ServiceComplex::where('service_id', $service->id)->where('company_id', $company_id)->count() > 0) {
                $serviceComplex = ServiceComplex::where('service_id', $service->id)->where('company_id', $company_id)->first();
            } else {
                $serviceComplex = new ServiceComplex;
            }

            $serviceComplex->service_id = $service->id;
            $serviceComplex->company_id = $company_id;
            $serviceComplex->price = $price;
            $serviceComplex->save();
        }

        return redirect('/services');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, FormBuilder $formBuilder)
    {
        $service = Service::find($id);
        $form = $formBuilder->create(\MMC\Forms\TM\ServiceForm::class, [
            'method' => 'PUT',
            'url' => '/services/'.$service->id,
            'model' => $service
        ]);

        $companies = \MMC\Models\Company::orderBy('id', 'desc')->get();
        return view('admin.service.create', compact('form', 'service', 'companies'));
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
        $form = $formBuilder->create(\MMC\Forms\TM\ServiceForm::class);

        if (!$form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        }

        $service = Service::find($id);
        $service->fill($request->all());
        $service->status = $request->has('status') ? 1 : 0;
        $service->is_complex = $request->has('is_complex') ? 1 : 0;
        $service->labor_exchange = $request->has('labor_exchange') ? 1 : 0;
        $service->save();

        foreach ($request->complex as $company_id => $price) {
            if (!$price) {
                $price = 0;
            }

            if (ServiceComplex::where('service_id', $service->id)->where('company_id', $company_id)->count() > 0) {
                $serviceComplex = ServiceComplex::where('service_id', $service->id)->where('company_id', $company_id)->first();
            } else {
                $serviceComplex = new ServiceComplex;
            }

            $serviceComplex->service_id = $service->id;
            $serviceComplex->company_id = $company_id;
            $serviceComplex->price = $price;
            $serviceComplex->save();
        }

        return redirect('/services');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Service::find($id)->delete();
    }
}
