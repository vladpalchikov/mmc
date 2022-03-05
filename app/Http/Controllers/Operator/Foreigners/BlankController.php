<?php

namespace MMC\Http\Controllers\Operator\Foreigners;

use Illuminate\Http\Request;

use MMC\Http\Requests;
use MMC\Http\Controllers\Controller;
use Kris\LaravelFormBuilder\FormBuilder;
use Auth;
use MMC\Models\Blank;
use MMC\Models\Foreigner;
use MMC\Models\ForeignerBlank;

class BlankController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($foreigner_id)
    {
        $blank = Blank::where('is_actual', true)->first();

        $currentMonth = date('m');
        $lastMonthBlank = ForeignerBlank::whereRaw('MONTH(created_at) = ?', [$currentMonth])->orderBy('created_at', 'desc');
        if ($lastMonthBlank->count() == 0) {
            $number = 1;
        } else {
            $lastMonthBlank = $lastMonthBlank->first();
            $number = $lastMonthBlank->number + 1;
        }

        $foreignerBlank = new ForeignerBlank;
        $foreignerBlank->blank_id = $blank->id;
        $foreignerBlank->operator_id = Auth::user()->id;
        $foreignerBlank->foreigner_id = $foreigner_id;
        $foreignerBlank->number = $number;
        $foreignerBlank->save();
        
        $foreignerBlank->full_number = $foreignerBlank->getNumber();
        $foreignerBlank->save();

        return redirect('/operator/foreigners/'.$foreigner_id.'/blank/'.$foreignerBlank->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($foreigner_id, $blank_id)
    {
        $blank = ForeignerBlank::find($blank_id);
        $foreigner = Foreigner::find($foreigner_id);
        return view('operator.foreigner.blank.view', compact('foreigner', 'blank'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($foreigner_id, $blank_id)
    {
        ForeignerBlank::find($blank_id)->delete();
    }
}
