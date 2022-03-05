<?php

namespace MMC\Http\Controllers\Operator;

use Illuminate\Http\Request;

use MMC\Http\Requests;
use MMC\Http\Controllers\Controller;
use Kris\LaravelFormBuilder\FormBuilder;
use Auth;
use Endroid\QrCode\QrCode;
use MMC\Models\ForeignerService;
use MMC\Models\Foreigner;
use MMC\Models\MMC;
use MMC\Models\UserMMC;

class CurrentServicesController extends Controller
{
	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $services = new ForeignerService;

        $mmcList = MMC::whereIn('id', Auth::user()->mmcListId())->get();

        if ($request->has('search')) {
            $search = trim($request->get('search'));
            $foreigners = \MMC\Models\Foreigner::orderBy('created_at', 'desc');
            $foreigners = $foreigners
                ->orWhereRaw('LOWER(surname)="'.mb_strtolower($search).'"')
                ->orWhereRaw('LOWER(name)="'.mb_strtolower($search).'"')
                ->orWhereRaw('LOWER(middle_name)="'.mb_strtolower($search).'"')
                ->orWhereRaw('LOWER(CONCAT(surname," ",name," ",middle_name))="'.mb_strtolower($search).'"')
                ->orWhereRaw('LOWER(CONCAT(surname," ",name))="'.mb_strtolower($search).'"')
                ->orWhereRaw('LOWER(CONCAT(surname," ",middle_name))="'.mb_strtolower($search).'"')
                ->orWhereRaw('LOWER(CONCAT(name," ",middle_name))="'.mb_strtolower($search).'"')
                ->orWhereRaw('CONCAT_WS("",document_series,document_number)="'.$search.'"')
                ->orWhere('document_number', $search)
                ->where('status', '<>', 2)->get()->pluck(['id'])->toArray();

            if (strlen($search) <= 6) {
                $services = $services->where('id', '=', $search);
            } else {
                $services = $services->whereIn('foreigner_id', $foreigners);
            }
        } else {
            $services = $services->whereRaw('(Date(created_at) = CURDATE() OR payment_status = 0)');
        }

        $operators = [];
        $operator = \MMC\Models\User::where('active', '=', 1)->orderBy('name', 'asc');

        if ($request->has('mmc')) {
            $mmcUsers = UserMMC::where('mmc_id', $request->get('mmc'))->pluck('user_id')->toArray();
            $operator = $operator->whereIn('id', $mmcUsers);
        } else {
            if (Auth::user()->hasRole('business.manager|business.managerbg|accountant|managertmsn|cashier')) {
                $mmcUsers = UserMMC::whereIn('mmc_id', Auth::user()->mmcListId())->pluck('user_id')->toArray();
                $operator = $operator->whereIn('id', $mmcUsers);
            }
        }

        $operatorIds = [];
        foreach ($operator->get() as $user) {
            if ($user->hasRole('managertmsn|managertm|managerbg')) {
                $operators[] = $user;
                $operatorIds[] = $user->id;
            }
        }

        if (Auth::user()->hasRole('managertm|managerbg')) {
            $services = $services->where('operator_id', '=', Auth::user()->id);
        }

        if (Auth::user()->hasRole('administrator|managertmsn|business.manager|business.managerbg|accountant|chief.accountant|cashier')) {
            if ($request->has('manager')) {
                $services = $services->where('operator_id', '=', $request->get('manager'));
            } else {
                $services = $services->whereIn('operator_id', $operatorIds);
            }
        }

        $services = $services->orderBy('created_at', 'desc')->paginate(50);
        $services->appends($request->input())->links();
		return view('operator.foreigner.current-services', compact('services', 'operators', 'mmcList'));
	}
}
