<?php

namespace MMC\Http\Controllers\Operator\Report;

use Illuminate\Http\Request;

use MMC\Http\Requests;
use MMC\Http\Controllers\Controller;
use Kris\LaravelFormBuilder\FormBuilder;
use Auth;
use Helper;
use DB;
use MMC\Models\ForeignerService;
use MMC\Models\MU\MUService;
use MMC\Models\Client;
use MMC\Models\UserMMC;

class DebitReportController extends Controller
{
	public $clients;
	public $mmc;
	public $daterange;
	public $totalUnpayedServices;
	public $totalUnpayedSum;

	public function __construct(Request $request)
	{
		$this->mmc = \MMC\Models\MMC::all();
		$this->daterange[0] = date('d.m.y');
		$this->daterange[1] = date('d.m.y');
		$this->totalUnpayedServices = 0;
		$this->totalUnpayedSum = 0;

		if (Auth::user()->hasRole('business.manager|accountant')) {
			$mmcUsers = UserMMC::whereIn('mmc_id', Auth::user()->mmcListId())->pluck('user_id')->toArray();
			$users = \MMC\Models\User::where('active', '=', 1)->whereIn('id', $mmcUsers)->orderBy('name', 'asc')->get();
		} else {
			$users = \MMC\Models\User::where('active', '=', 1)->orderBy('name', 'asc')->get();
		}


		if ($request->has('client')) {
			$client = Client::find($request->get('client'));
			$this->clients[$client->id] = $client;
		} else {
			foreach (Client::orderBy('name', 'asc')->get() as $client) {
				$this->clients[$client->id] = $client;
			}
		}

		$servicesTM = new ForeignerService;
		$servicesMU = new MUService;

		$servicesTM = $servicesTM->where('repayment_status', '<>', 3)->where('payment_status', '=', 0)->where('payment_method', '=', 1);
		$servicesMU = $servicesMU->where('repayment_status', '<>', 3)->where('payment_status', '=', 0)->where('payment_method', '=', 1);

		// if (Auth::user()->hasRole('business.manager|accountant')) {
		// 	$operatorIds = \MMC\Models\User::where('mmc_id', '=', Auth::user()->mmc_id)->get()->pluck(['id'])->toArray();
		// 	$servicesTM = $servicesTM->whereIn('operator_id', $operatorIds);
		// 	$servicesMU = $servicesMU->whereIn('operator_id', $operatorIds);
		// }

		// if ($request->has('mmc')) {
		// 	$operatorIds = \MMC\Models\User::where('mmc_id', '=', $request->get('mmc'))->get()->pluck(['id'])->toArray();
		// 	$servicesTM = $servicesTM->whereIn('operator_id', $operatorIds);
		// 	$servicesMU = $servicesMU->whereIn('operator_id', $operatorIds);
		// }

		if ($request->has('daterange')) {
			$this->daterange = explode('-', $request->get('daterange'));
			if (count($this->daterange) == 1) {
				$servicesTM = $servicesTM->whereDate('created_at', '=', Helper::formatDateForQuery($this->daterange[0]));
				$servicesMU = $servicesMU->whereDate('created_at', '=', Helper::formatDateForQuery($this->daterange[0]));
			} else {
				$servicesTM = $servicesTM->whereDate('created_at', '>=', Helper::formatDateForQuery($this->daterange[0]));
				$servicesMU = $servicesMU->whereDate('created_at', '>=', Helper::formatDateForQuery($this->daterange[0]));

				$servicesTM = $servicesTM->whereDate('created_at', '<=', Helper::formatDateForQuery($this->daterange[1]));
				$servicesMU = $servicesMU->whereDate('created_at', '<=', Helper::formatDateForQuery($this->daterange[1]));
			}
		} else {
			$servicesTM = $servicesTM->whereDate('created_at', '=', \Carbon\Carbon::today()->toDateString());
			$servicesMU = $servicesMU->whereDate('created_at', '=', \Carbon\Carbon::today()->toDateString());
		}

		foreach ($this->clients as $client) {
			$clientServiceTM = clone $servicesTM;
			$clientServiceMU = clone $servicesMU;

			$client->unpayedServices += $clientServiceTM->where('client_id', '=', $client->id)->count();
			$client->unpayedServices += $clientServiceMU->where('client_id', '=', $client->id)->sum('service_count');

			$this->totalUnpayedServices += $client->unpayedServices;
			if ($client->unpayedServices > 0) {
				$client->unpayedSum += $clientServiceTM->where('client_id', '=', $client->id)->sum('service_price');
				$client->unpayedSum += $clientServiceMU->where('client_id', '=', $client->id)->sum(DB::raw('service_count * service_price'));
				$this->totalUnpayedSum += $client->unpayedSum;
			} else {
				// unset($this->clients[$client->id]);
			}
			unset($clientServiceTM);
			unset($clientServiceMU);
		}
	}

	public function index()
	{
		return view('report.debit.index', [
			'clients' => $this->clients,
			'mmc' => $this->mmc,
			'daterange' => $this->daterange,
			'clients' => $this->clients,
			'totalUnpayedSum' => $this->totalUnpayedSum,
			'totalUnpayedServices' => $this->totalUnpayedServices,
		]);
	}

	public function print()
	{
		return view('report.debit.print', [
			'clients' => $this->clients,
			'mmc' => $this->mmc,
			'daterange' => $this->daterange,
			'clients' => $this->clients,
			'totalUnpayedSum' => $this->totalUnpayedSum,
			'totalUnpayedServices' => $this->totalUnpayedServices,
		]);
	}
}