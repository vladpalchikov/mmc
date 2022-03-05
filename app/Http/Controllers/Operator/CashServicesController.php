<?php

namespace MMC\Http\Controllers\Operator;

use Illuminate\Http\Request;

use MMC\Http\Requests;
use MMC\Http\Controllers\Controller;
use Kris\LaravelFormBuilder\FormBuilder;
use Auth;
use Endroid\QrCode\QrCode;
use Carbon\Carbon;

use MMC\Models\Client;
use MMC\Models\Foreigner;
use MMC\Models\ForeignerService;
use MMC\Models\ForeignerServiceGroup;

class CashServicesController extends Controller
{
	public function index(Request $request)
    {
        foreach (Client::orderBy('name', 'asc')->get() as $client) {
            $clients[] = $client;
        }
        if ($request->has('module')) {
        	$services = new ForeignerServiceGroup;
    	} else {
        	$services = ForeignerService::where('is_mu', 0);
    	}

    	$services = $services->where('payment_status', 0)->where('payment_method', 0);

        if ($request->has('client')) {
            $services = $services->where('client_id', $request->get('client'));            
        }

        if ($request->has('search')) {
            $search = trim($request->get('search'));
            $foreigners = Foreigner::orderBy('created_at', 'desc');
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

        $services = $services->orderBy('created_at', 'desc')->paginate(50);
        $services->appends($request->input())->links();
		return view('operator.foreigner.cash-services', compact('services', 'clients'));
	}

    public function pay(Request $request)
    {
        if ($request->has('id') && $request->has('type')) {
            $id = $request->get('id');
            $type = $request->get('type');
            if ($type == 'tm') {
                $service = ForeignerService::find($id);
                $service->payment_status = 1;
                $service->cashier_id = Auth::user()->id;
                $service->payment_at = Carbon::now();
                $service->save();
            } else {
                $group = ForeignerServiceGroup::find($id);
                $group->payment_status = 1;
                $group->cashier_id = Auth::user()->id;
                $group->payment_at = Carbon::now();
                $group->save();

                foreach ($group->services as $service) {
                    $service->payment_status = 1;
                    $service->cashier_id = Auth::user()->id;
                    $service->payment_at = Carbon::now();
                    $service->save();
                }
            }
        }
    }
}