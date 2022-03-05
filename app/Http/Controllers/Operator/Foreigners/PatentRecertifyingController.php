<?php

namespace MMC\Http\Controllers\Operator\Foreigners;

use Illuminate\Http\Request;

use MMC\Http\Requests;
use MMC\Http\Controllers\Controller;
use MMC\Models\ForeignerPatentRecertifying;
use MMC\Models\ForeignerReg;
use Kris\LaravelFormBuilder\FormBuilder;
use Auth;
use Carbon\Carbon;

class PatentRecertifyingController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($foreigner_id, FormBuilder $formBuilder)
    {
        $form = $formBuilder->create(\MMC\Forms\TM\ForeignerPatentRecertifyingForm::class, [
            'method' => 'POST',
            'url' => '/operator/foreigners/'.$foreigner_id.'/patentrecertifying',
            'data' => \MMC\Models\Foreigner::find($foreigner_id)->toArray()
        ]);

        return view('operator.foreigner.patent_recertifying.create', compact('form'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($foreigner_id, Request $request, FormBuilder $formBuilder)
    {
        $form = $formBuilder->create(\MMC\Forms\TM\ForeignerPatentRecertifyingForm::class, [
            'data' => \MMC\Models\Foreigner::find($foreigner_id)->toArray()
        ]);

        if (!$form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        }

        $foreignerPatent = new ForeignerPatentRecertifying;
        $foreignerPatent->fill($request->all());
        $foreignerPatent->foreigner_id = $foreigner_id;
        $foreignerPatent->operator_id = Auth::user()->id;
        $foreignerPatent->updated_by = Auth::user()->id;
        $foreignerPatent->save();

        $foreigner = \MMC\Models\Foreigner::find($foreigner_id);
        $foreigner->fill($request->all());
        $foreigner->updated_by = Auth::user()->id;
        $foreigner->save();

        $foreignerReg = new ForeignerReg;
        $foreignerReg->type = 'patent_recertifying';
        $foreignerReg->foreigner_id = $foreigner_id;
        $foreignerReg->client_id = $foreigner->host_id;
        $foreignerReg->operator_id = Auth::user()->id;
        $foreignerReg->foreigner_address = $foreigner->address.' '.$foreigner->address_line2.' '.$foreigner->address_line3;
        $foreignerReg->save();

        return redirect('/operator/foreigners/'.$foreigner_id.'/patentrecertifying/'.$foreignerPatent->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($foreigner_id, $patent_id)
    {
        $patent = ForeignerPatentRecertifying::find($patent_id);
        $foreigner = \MMC\Models\Foreigner::find($foreigner_id);

        return view('operator.foreigner.patent_recertifying.view', compact('foreigner', 'patent'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($foreigner_id, $patent_id, FormBuilder $formBuilder)
    {
        $patent = ForeignerPatentRecertifying::findOrFail($patent_id);
        $foreigner = \MMC\Models\Foreigner::find($foreigner_id);
        $form = $formBuilder->create(\MMC\Forms\TM\ForeignerPatentRecertifyingForm::class, [
            'method' => 'PUT',
            'data' => $foreigner->toArray(),
            'url' => '/operator/foreigners/'.$foreigner_id.'/patentrecertifying/'.$patent_id,
            'model' => $patent
        ]);
        
        return view('operator.foreigner.patent_recertifying.create', compact('form', 'foreigner'));
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
        $form = $formBuilder->create(\MMC\Forms\TM\ForeignerPatentRecertifyingForm::class, [
            'data' => \MMC\Models\Foreigner::find($foreigner_id)->toArray()
        ]);

        if (!$form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        }

        $foreignerPatent = ForeignerPatentRecertifying::find($patent_id);
        $foreignerPatent->fill($request->all());
        $foreignerPatent->updated_by = Auth::user()->id;
        $foreignerPatent->save();

        $foreigner = \MMC\Models\Foreigner::find($foreigner_id);
        $foreigner->fill($request->all());
        $foreigner->updated_by = Auth::user()->id;
        $foreigner->save();

        return redirect('/operator/foreigners/'.$foreigner_id.'/patentrecertifying/'.$foreignerPatent->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($foreigner_id, $patent_id)
    {
        ForeignerPatentRecertifying::find($patent_id)->delete();
    }

    public function registry($foreigner_id, $patent_id)
    {
        $foreignerPatent = ForeignerPatentRecertifying::find($patent_id);
        $foreignerPatent->doc_status = 1;
        $foreignerPatent->reg_at = Carbon::now();
        $foreignerPatent->uo_user = Auth::user()->id;
        $foreignerPatent->save();

        return $foreignerPatent->id;
    }
}
