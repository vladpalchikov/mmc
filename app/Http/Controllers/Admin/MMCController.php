<?php

namespace MMC\Http\Controllers\Admin;

use Illuminate\Http\Request;

use MMC\Http\Requests;
use MMC\Http\Controllers\Controller;
use Kris\LaravelFormBuilder\FormBuilder;

class MMCController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $mmc = \MMC\Models\MMC::orderBy('id', 'desc')->get();
        return view('admin.mmc.index', compact('mmc'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(FormBuilder $formBuilder)
    {
        $form = $formBuilder->create(\MMC\Forms\MMCForm::class, [
            'method' => 'POST',
            'url' => '/admin/mmc'
        ]);

        return view('admin.mmc.create', compact('form'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, FormBuilder $formBuilder)
    {
        $form = $formBuilder->create(\MMC\Forms\MMCForm::class);

        if (!$form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        }

        $company = new \MMC\Models\MMC;
        $company->fill($request->all());
        $company->save();

        return redirect('/admin/mmc');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, FormBuilder $formBuilder)
    {
        $mmc = \MMC\Models\MMC::find($id);
        $form = $formBuilder->create(\MMC\Forms\MMCForm::class, [
            'method' => 'PUT',
            'url' => '/admin/mmc/'.$mmc->id,
            'model' => $mmc
        ]);

        return view('admin.mmc.create', compact('form'));
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
        $form = $formBuilder->create(\MMC\Forms\MMCForm::class);

        if (!$form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        }

        $company = \MMC\Models\MMC::find($id);
        $company->fill($request->all());
        $company->save();

        return redirect('/admin/mmc');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        \MMC\Models\MMC::find($id)->delete();
    }
}
