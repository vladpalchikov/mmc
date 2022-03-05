<?php

namespace MMC\Http\Controllers\Operator\Foreigners;

use Auth;
use Illuminate\Http\Request;
use Kris\LaravelFormBuilder\FormBuilder;
use MMC\Forms\TM\ForeignerPatentChangeForm;
use MMC\Http\Controllers\Controller;
use MMC\Http\Requests;
use MMC\Models\Foreigner;
use MMC\Models\ForeignerPatent;
use MMC\Models\ForeignerPatentChange;

class PatentChangeController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($foreigner_id, FormBuilder $formBuilder)
    {
        $form = $formBuilder->create(ForeignerPatentChangeForm::class, [
            'method' => 'POST',
            'url' => '/operator/foreigners/'.$foreigner_id.'/patentchange',
            'data' => Foreigner::find($foreigner_id)->toArray(),
        ]);

        return view('operator.foreigner.patent_change.create', compact('form'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($foreigner_id, Request $request, FormBuilder $formBuilder)
    {
        $form = $formBuilder->create(ForeignerPatentChangeForm::class, [
            'data' => Foreigner::find($foreigner_id)->toArray()
        ]);

        if (!$form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        }

        $foreignerPatentChange = new ForeignerPatentChange;
        $foreignerPatentChange->fill($request->all());
        $foreignerPatentChange->foreigner_id = $foreigner_id;
        $foreignerPatentChange->operator_id = Auth::user()->id;
        $foreignerPatentChange->updated_by = Auth::user()->id;
        $foreignerPatentChange->save();

        $foreigner = Foreigner::find($foreigner_id);
        $foreigner->fill($request->all());
        $foreigner->updated_by = Auth::user()->id;
        $foreigner->save();

        return redirect('/operator/foreigners/'.$foreigner_id.'/patentchange/'.$foreignerPatentChange->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($foreigner_id, $patent_change_id)
    {
        $foreigner = Foreigner::find($foreigner_id);
        $patentChange = ForeignerPatentChange::find($patent_change_id);
        $patent = ForeignerPatent::where('foreigner_id', '=', $foreigner->id)->first();

        return view('operator.foreigner.patent_change.view', compact('foreigner', 'patent', 'patentChange'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($foreigner_id, $patent_id, FormBuilder $formBuilder)
    {
        $patent = ForeignerPatentChange::findOrFail($patent_id);
        $foreigner = Foreigner::find($foreigner_id);
        $form = $formBuilder->create(ForeignerPatentChangeForm::class, [
            'method' => 'PUT',
            'data' => $foreigner->toArray(),
            'url' => '/operator/foreigners/'.$foreigner_id.'/patentchange/'.$patent_id,
            'model' => $patent
        ]);

        return view('operator.foreigner.patent_change.create', compact('form', 'foreigner'));
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
        $form = $formBuilder->create(ForeignerPatentChangeForm::class, [
            'data' => Foreigner::find($foreigner_id)->toArray()
        ]);

        if (!$form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        }

        $foreignerPatentChange = ForeignerPatentChange::find($patent_id);
        $foreignerPatentChange->fill($request->all());
        $foreignerPatentChange->updated_by = Auth::user()->id;
        $foreignerPatentChange->save();

        $foreigner = Foreigner::find($foreigner_id);
        $foreigner->fill($request->all());
        $foreigner->updated_by = Auth::user()->id;
        $foreigner->save();

        return redirect('/operator/foreigners/'.$foreigner_id.'/patentchange/'.$foreignerPatentChange->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($foreigner_id, $patent_id)
    {
        ForeignerPatentChange::find($patent_id)->delete();
    }
}
