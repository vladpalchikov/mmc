<?php

namespace MMC\Http\Controllers\Operator\Foreigners;

use Illuminate\Http\Request;

use MMC\Http\Requests;
use MMC\Http\Controllers\Controller;
use Kris\LaravelFormBuilder\FormBuilder;
use Auth;

class DmsController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($foreigner_id, FormBuilder $formBuilder)
    {
        $form = $formBuilder->create(\MMC\Forms\TM\ForeignerDmsForm::class, [
            'method' => 'POST',
            'url' => '/operator/foreigners/'.$foreigner_id.'/dms',
            'data' => \MMC\Models\Foreigner::find($foreigner_id)->toArray()
        ]);

        return view('operator.foreigner.dms.create', compact('form'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($foreigner_id, Request $request, FormBuilder $formBuilder)
    {
        $form = $formBuilder->create(\MMC\Forms\TM\ForeignerDmsForm::class, [
            'data' => \MMC\Models\Foreigner::find($foreigner_id)->toArray()
        ]);

        if (!$form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        }

        $foreignerDms = new \MMC\Models\ForeignerDms;
        $foreignerDms->fill($request->all());
        $foreignerDms->foreigner_id = $foreigner_id;
        $foreignerDms->operator_id = Auth::user()->id;
        $foreignerDms->updated_by = Auth::user()->id;
        $foreignerDms->save();

        $foreigner = \MMC\Models\Foreigner::find($foreigner_id);
        $foreigner->fill($request->all());
        $foreigner->updated_by = Auth::user()->id;
        $foreigner->save();

        return redirect('/operator/foreigners/'.$foreigner_id.'/dms/'.$foreignerDms->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($foreigner_id, $dms_id)
    {
        $dms = \MMC\Models\ForeignerDms::find($dms_id);
        $foreigner = \MMC\Models\Foreigner::find($foreigner_id);
        return view('operator.foreigner.dms.view', compact('foreigner', 'dms'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($foreigner_id, $dms_id, FormBuilder $formBuilder)
    {
        $dms = \MMC\Models\ForeignerDms::findOrFail($dms_id);
        $foreigner = \MMC\Models\Foreigner::find($foreigner_id);
        $form = $formBuilder->create(\MMC\Forms\TM\ForeignerDmsForm::class, [
            'method' => 'PUT',
            'data' => $foreigner->toArray(),
            'url' => '/operator/foreigners/'.$foreigner_id.'/dms/'.$dms_id,
            'model' => $dms
        ]);
        
        return view('operator.foreigner.dms.create', compact('form', 'foreigner'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($foreigner_id, $dms_id, Request $request, FormBuilder $formBuilder)
    {
        $form = $formBuilder->create(\MMC\Forms\TM\ForeignerDmsForm::class, [
            'data' => \MMC\Models\Foreigner::find($foreigner_id)->toArray()
        ]);

        if (!$form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        }

        $foreignerDms = \MMC\Models\ForeignerDms::find($dms_id);
        $foreignerDms->fill($request->all());
        $foreignerDms->updated_by = Auth::user()->id;
        $foreignerDms->save();

        $foreigner = \MMC\Models\Foreigner::find($foreigner_id);
        $foreigner->fill($request->all());
        $foreigner->updated_by = Auth::user()->id;
        $foreigner->save();

        return redirect('/operator/foreigners/'.$foreigner_id.'/dms/'.$foreignerDms->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($foreigner_id, $dms_id)
    {
        \MMC\Models\ForeignerDms::find($dms_id)->delete();
    }
}
