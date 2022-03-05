<?php

namespace MMC\Http\Controllers\Admin;

use Illuminate\Http\Request;

use MMC\Http\Requests;
use MMC\Http\Controllers\Controller;
use Kris\LaravelFormBuilder\FormBuilder;

class UpdateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $updates = \MMC\Models\Update::orderBy('id', 'desc')->get();
        return view('admin.update.index', compact('updates'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(FormBuilder $formBuilder)
    {
        $form = $formBuilder->create(\MMC\Forms\UpdateForm::class, [
            'method' => 'POST',
            'url' => '/admin/updates'
        ]);

        return view('admin.update.create', compact('form'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, FormBuilder $formBuilder)
    {
        $form = $formBuilder->create(\MMC\Forms\UpdateForm::class);

        if (!$form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        }

        $update = new \MMC\Models\Update;
        $update->fill($request->all());
        $update->save();

        return redirect('/admin/updates');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, FormBuilder $formBuilder)
    {
        $update = \MMC\Models\Update::find($id);
        $form = $formBuilder->create(\MMC\Forms\UpdateForm::class, [
            'method' => 'PUT',
            'url' => '/admin/updates/'.$update->id,
            'model' => $update
        ]);

        return view('admin.update.create', compact('form'));
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
        $form = $formBuilder->create(\MMC\Forms\UpdateForm::class);

        if (!$form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        }

        $update = \MMC\Models\Update::find($id);
        $update->fill($request->all());
        $update->save();

        return redirect('/admin/updates');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        \MMC\Models\Update::find($id)->delete();
    }
}
