<?php

namespace MMC\Http\Controllers\Operator\Foreigners;

use Illuminate\Http\Request;

use MMC\Http\Requests;
use MMC\Http\Controllers\Controller;
use Kris\LaravelFormBuilder\FormBuilder;
use Auth;

class IgController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($foreigner_id, FormBuilder $formBuilder)
    {
        $form = $formBuilder->create(\MMC\Forms\TM\ForeignerIgForm::class, [
            'method' => 'POST',
            'url' => '/operator/foreigners/'.$foreigner_id.'/ig',
            'data' => \MMC\Models\Foreigner::find($foreigner_id)->toArray()
        ]);

        return view('operator.foreigner.ig.create', compact('form'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($foreigner_id, Request $request, FormBuilder $formBuilder)
    {
        $form = $formBuilder->create(\MMC\Forms\TM\ForeignerIgForm::class, [
            'data' => \MMC\Models\Foreigner::find($foreigner_id)->toArray()
        ]);

        if (!$form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        }

        $foreignerIg = new \MMC\Models\ForeignerIg;
        $foreignerIg->fill($request->all());
        $foreignerIg->foreigner_id = $foreigner_id;
        $foreignerIg->operator_id = Auth::user()->id;
        $foreignerIg->updated_by = Auth::user()->id;
        $foreignerIg->save();

        $foreigner = \MMC\Models\Foreigner::find($foreigner_id);
        $foreigner->fill($request->all());
        $foreigner->updated_by = Auth::user()->id;
        $foreigner->save();

        return redirect('/operator/foreigners/'.$foreigner_id.'/ig/'.$foreignerIg->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($foreigner_id, $ig_id)
    {
        $ig = \MMC\Models\ForeignerIg::find($ig_id);
        $foreigner = \MMC\Models\Foreigner::find($foreigner_id);

        return view('operator.foreigner.ig.view', compact('foreigner', 'ig'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($foreigner_id, $ig_id, FormBuilder $formBuilder)
    {
        $ig = \MMC\Models\ForeignerIg::findOrFail($ig_id);
        $foreigner = \MMC\Models\Foreigner::find($foreigner_id);
        $form = $formBuilder->create(\MMC\Forms\TM\ForeignerIgForm::class, [
            'method' => 'PUT',
            'data' => $foreigner->toArray(),
            'url' => '/operator/foreigners/'.$foreigner_id.'/ig/'.$ig_id,
            'model' => $ig
        ]);
        
        return view('operator.foreigner.ig.create', compact('form', 'foreigner'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($foreigner_id, $ig_id, Request $request, FormBuilder $formBuilder)
    {
        $form = $formBuilder->create(\MMC\Forms\TM\ForeignerIgForm::class, [
            'data' => \MMC\Models\Foreigner::find($foreigner_id)->toArray()
        ]);

        if (!$form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        }

        $foreignerIg = \MMC\Models\ForeignerIg::find($ig_id);
        $foreignerIg->fill($request->all());
        $foreignerIg->updated_by = Auth::user()->id;
        $foreignerIg->save();

        $foreigner = \MMC\Models\Foreigner::find($foreigner_id);
        $foreigner->fill($request->all());
        $foreigner->updated_by = Auth::user()->id;
        $foreigner->save();

        return redirect('/operator/foreigners/'.$foreigner_id.'/ig/'.$foreignerIg->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($foreigner_id, $ig_id)
    {
        \MMC\Models\ForeignerIg::find($ig_id)->delete();
    }
}
