<?php

namespace MMC\Http\Controllers\Operator\Foreigners;

use Illuminate\Http\Request;

use MMC\Http\Requests;
use MMC\Http\Controllers\Controller;
use MMC\Models\ForeignerReg;
use MMC\Models\ForeignerPatent;
use MMC\Models\Foreigner;
use MMC\Forms\TM\ForeignerPatentForm;
use Kris\LaravelFormBuilder\FormBuilder;
use Auth;
use Carbon\Carbon;

class PatentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return redirect()->back();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($foreigner_id, FormBuilder $formBuilder)
    {
        $form = $formBuilder->create(ForeignerPatentForm::class, [
            'method' => 'POST',
            'url' => '/operator/foreigners/'.$foreigner_id.'/patent',
            'data' => Foreigner::find($foreigner_id)->toArray()
        ]);

        return view('operator.foreigner.patent.create', compact('form'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($foreigner_id, Request $request, FormBuilder $formBuilder)
    {
        $form = $formBuilder->create(ForeignerPatentForm::class, [
            'data' => Foreigner::find($foreigner_id)->toArray()
        ]);

        if (!$form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        }

        $foreignerPatent = new ForeignerPatent;
        $foreigner = Foreigner::find($foreigner_id);
        $foreignerPatent->fill($request->all());
        $foreignerPatent->foreigner_id = $foreigner_id;
        $foreignerPatent->operator_id = Auth::user()->id;
        $foreignerPatent->updated_by = Auth::user()->id;
        $foreignerPatent->surname = $foreigner->surname;
        $foreignerPatent->middle_name = $foreigner->middle_name;
        $foreignerPatent->name = $foreigner->name;
        $foreignerPatent->birthday = $foreigner->birthday;
        $foreignerPatent->gender = $foreigner->gender;
        $foreignerPatent->nationality = $foreigner->nationality;
        $foreignerPatent->nationality_line2 = $foreigner->nationality_line2;
        $foreignerPatent->document_name = $foreigner->document_name;
        $foreignerPatent->document_series = $foreigner->document_series;
        $foreignerPatent->document_number = $foreigner->document_number;
        $foreignerPatent->document_date = $foreigner->document_date;
        $foreignerPatent->address = $foreigner->address;
        $foreignerPatent->address_line2 = $foreigner->address_line2;
        $foreignerPatent->address_line3 = $foreigner->address_line3;
        $foreignerPatent->save();

        $foreigner->fill($request->except('inn'));
        $foreigner->updated_by = Auth::user()->id;
        $foreigner->name_change = $foreignerPatent->name_change;
        $foreigner->birthday_place = $foreignerPatent->birthday_place;
        $foreigner->registration_address = $foreignerPatent->registration_address;
        $foreigner->registration_address_line2 = $foreignerPatent->registration_address_line2;
        $foreigner->russian_document = $foreignerPatent->russian_document;
        $foreigner->russian_document_line2 = $foreignerPatent->russian_document_line2;
        $foreigner->russian_number = $foreignerPatent->russian_number;
        $foreigner->russian_series = $foreignerPatent->russian_series;
        $foreigner->russian_date = $foreignerPatent->russian_date;
        $foreigner->work_activity = $foreignerPatent->work_activity;
        $foreigner->prev_patent = $foreignerPatent->prev_patent;
        $foreigner->prev_patent_line2 = $foreignerPatent->prev_patent_line2;
        $foreigner->prev_patent_series = $foreignerPatent->prev_patent_series;
        $foreigner->prev_patent_number = $foreignerPatent->prev_patent_number;
        $foreigner->prev_patent_blank_series = $foreignerPatent->prev_patent_blank_series;
        $foreigner->prev_patent_blank_number = $foreignerPatent->prev_patent_blank_number;
        $foreigner->prev_patent_date_from = $foreignerPatent->prev_patent_date_from;
        $foreigner->prev_patent_date_to = $foreignerPatent->prev_patent_date_to;
        $foreigner->phone = $foreignerPatent->phone;
        if (!empty($request->get('inn'))) {
            $foreigner->inn = $request->get('inn');
        }
        $foreigner->save();

        $foreignerReg = new ForeignerReg;
        $foreignerReg->type = 'patent';
        $foreignerReg->foreigner_id = $foreigner_id;
        $foreignerReg->client_id = $foreigner->host_id;
        $foreignerReg->operator_id = Auth::user()->id;
        $foreignerReg->foreigner_address = $foreigner->address.' '.$foreigner->address_line2.' '.$foreigner->address_line3;
        $foreignerReg->save();

        return redirect('/operator/foreigners/'.$foreigner_id.'/patent/'.$foreignerPatent->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($foreigner_id, $patent_id)
    {
        $patent = ForeignerPatent::find($patent_id);
        $foreigner = Foreigner::find($foreigner_id);

        return view('operator.foreigner.patent.view', compact('foreigner', 'patent'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($foreigner_id, $patent_id, FormBuilder $formBuilder)
    {
        $patent = ForeignerPatent::findOrFail($patent_id);
        $foreigner = Foreigner::find($foreigner_id);
        $form = $formBuilder->create(ForeignerPatentForm::class, [
            'method' => 'PUT',
            'data' => $foreigner->toArray(),
            'url' => '/operator/foreigners/'.$foreigner_id.'/patent/'.$patent_id,
            'model' => $patent
        ]);

        return view('operator.foreigner.patent.create', compact('form', 'foreigner'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($foreigner_id, $patent_id, Request $request, FormBuilder $formBuilder)
    {
        $form = $formBuilder->create(ForeignerPatentForm::class, [
            'data' => Foreigner::find($foreigner_id)->toArray()
        ]);

        if (!$form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        }

        $foreignerPatent = ForeignerPatent::find($patent_id);
        $foreignerPatent->fill($request->all());
        $foreignerPatent->updated_by = Auth::user()->id;
        $foreignerPatent->save();

        $foreigner = Foreigner::find($foreigner_id);
        $foreigner->fill($request->except('inn'));
        $foreigner->updated_by = Auth::user()->id;
        if (!empty($request->get('inn'))) {
            $foreigner->inn = $request->get('inn');
        }
        $foreigner->save();

        return redirect('/operator/foreigners/'.$foreigner_id.'/patent/'.$foreignerPatent->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($foreigner_id, $patent_id)
    {
        ForeignerPatent::find($patent_id)->delete();
    }

    public function registry($foreigner_id, $patent_id)
    {
        $foreignerPatent = ForeignerPatent::find($patent_id);
        $foreignerPatent->doc_status = 1;
        $foreignerPatent->reg_at = Carbon::now();
        $foreignerPatent->uo_user = Auth::user()->id;
        $foreignerPatent->save();

        return $foreignerPatent->id;
    }
}
