<?php

namespace MMC\Http\Controllers\Admin;

use Illuminate\Http\Request;

use MMC\Http\Requests;
use MMC\Http\Controllers\Controller;
use Kris\LaravelFormBuilder\FormBuilder;
use MMC\Models\Blank;
use Auth;

class BlankController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $blanks = Blank::orderBy('created_at', 'desc')->get();

        return view('admin.blank.index', compact('blanks'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        foreach (Blank::all() as $oldBlank) {
            $oldBlank->is_actual = false;
            $oldBlank->save();
        }

        $blank = new Blank;
        $blank->is_actual = true;
        $blank->user_id = Auth::user()->id;

        if ($request->hasFile('side_a')) {
            $path = $request->file('side_a')->store('blanks_documents');
            $blank->side_a = basename($path);
        }

        if ($request->hasFile('side_b')) {
            $path = $request->file('side_b')->store('blanks_documents');
            $blank->side_b = basename($path);
        }

        $blank->save();

        return redirect('/blanks');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Blank::find($id)->delete();
    }

    public function file($id, Request $request)
    {
        $blank = Blank::find($id);

        if ($request->get('file') == 'side_a') {
            return response()->file(storage_path().'/app/blanks_documents/'.$blank->side_a);
        } else {
            return response()->file(storage_path().'/app/blanks_documents/'.$blank->side_b);
        }
    }
}
