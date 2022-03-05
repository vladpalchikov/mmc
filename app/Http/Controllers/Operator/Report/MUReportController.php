<?php

namespace MMC\Http\Controllers\Operator\Report;

use Illuminate\Http\Request;

use MMC\Http\Requests;
use MMC\Http\Controllers\Controller;
use Kris\LaravelFormBuilder\FormBuilder;
use Auth;
use Helper;
use MMC\Models\ForeignerService;
use MMC\Models\ForeignerServiceGroup;
use MMC\Models\ForeignerDms;
use MMC\Models\ForeignerIg;
use MMC\Models\ForeignerPatent;
use MMC\Models\ForeignerPatentChange;
use MMC\Models\ForeignerPatentRecertifying;
use MMC\Models\Client;
use MMC\Models\User;
use MMC\Models\UserMMC;
use Storage;

class MUReportController extends Controller
{
	public $reportServices;
	public $serviceTitles;
	public $totalServices;
	public $totalPayment;
	public $totalPrice;
	public $totalIndividualPrice;
	public $totalLegalPrice;
	public $totalIndividualServices;
	public $totalLegalServices;
	public $totalDocuments;
	public $operators;
	public $clients;
	public $daterange;
	public $currectClient;
	public $currectManager;

	public function __construct(Request $request)
	{
		$this->reportServices = new ForeignerServiceGroup;
		$this->daterange[0] = date('d.m.y');
		$this->daterange[1] = date('d.m.y');
		$operatorIds = [];
		$this->operators = [];

		$documentDms = new ForeignerDms;
		$documentIg = new ForeignerIg;
		$documentPatent = new ForeignerPatent;
		$documentPatentchange = new ForeignerPatentChange;
		$documentPatentrecertifying = new ForeignerPatentRecertifying;

		if (Auth::user()->hasRole('business.manager|accountant|managermusn')) {
			$mmcUsers = UserMMC::whereIn('mmc_id', Auth::user()->mmcListId())->pluck('user_id')->toArray();
			foreach (User::where('active', '=', 1)->whereIn('id', $mmcUsers)->orderBy('name', 'asc')->get() as $user) {
				if ($user->hasRole('managermusn|managermu')) {
					$this->operators[] = $user;
					$operatorIds[] = $user->id;
				}
			}
		} else {
			foreach (User::where('active', '=', 1)->orderBy('name', 'asc')->get() as $user) {
				if ($user->hasRole('managermusn|managermu')) {
					$this->operators[] = $user;
					$operatorIds[] = $user->id;
				}
			}
		}

		foreach (Client::orderBy('name', 'asc')->where('is_host_only', 0)->get() as $client) {
			$this->clients[] = $client;
		}

		if ($request->has('client')) {
			$this->reportServices = $this->reportServices->where('client_id', '=', $request->get('client'));
			$this->currectClient = $request->get('client');
		}

		if ($request->has('daterange')) {
			$this->daterange = explode('-', $request->get('daterange'));
			if (count($this->daterange) == 1) {
				$this->reportServices = $this->reportServices->whereDate('created_at' , '=', Helper::formatDateForQuery($this->daterange[0]));

				$documentDms = $documentDms->whereDate('created_at' , '=', Helper::formatDateForQuery($this->daterange[0]));
				$documentIg = $documentIg->whereDate('created_at' , '=', Helper::formatDateForQuery($this->daterange[0]));
				$documentPatent = $documentPatent->whereDate('created_at' , '=', Helper::formatDateForQuery($this->daterange[0]));
				$documentPatentchange = $documentPatentchange->whereDate('created_at' , '=', Helper::formatDateForQuery($this->daterange[0]));
				$documentPatentrecertifying = $documentPatentrecertifying->whereDate('created_at' , '=', Helper::formatDateForQuery($this->daterange[0]));
			} else {
				$this->reportServices = $this->reportServices->whereDate('created_at' , '>=', Helper::formatDateForQuery($this->daterange[0]));
				$this->reportServices = $this->reportServices->whereDate('created_at' , '<=', Helper::formatDateForQuery($this->daterange[1]));

				$documentDms = $documentDms->whereDate('created_at' , '>=', Helper::formatDateForQuery($this->daterange[0]));
				$documentIg = $documentIg->whereDate('created_at' , '>=', Helper::formatDateForQuery($this->daterange[0]));
				$documentPatent = $documentPatent->whereDate('created_at' , '>=', Helper::formatDateForQuery($this->daterange[0]));
				$documentPatentchange = $documentPatentchange->whereDate('created_at' , '>=', Helper::formatDateForQuery($this->daterange[0]));
				$documentPatentrecertifying = $documentPatentrecertifying->whereDate('created_at' , '>=', Helper::formatDateForQuery($this->daterange[0]));

				$documentDms = $documentDms->whereDate('created_at' , '<=', Helper::formatDateForQuery($this->daterange[1]));
				$documentIg = $documentIg->whereDate('created_at' , '<=', Helper::formatDateForQuery($this->daterange[1]));
				$documentPatent = $documentPatent->whereDate('created_at' , '<=', Helper::formatDateForQuery($this->daterange[1]));
				$documentPatentchange = $documentPatentchange->whereDate('created_at' , '<=', Helper::formatDateForQuery($this->daterange[1]));
				$documentPatentrecertifying = $documentPatentrecertifying->whereDate('created_at' , '<=', Helper::formatDateForQuery($this->daterange[1]));
			}
		} else {
			$this->reportServices = $this->reportServices->whereDate('created_at', '=', \Carbon\Carbon::today()->toDateString());

			$documentDms = $documentDms->whereRaw('Date(created_at) = CURDATE()');
			$documentIg = $documentIg->whereRaw('Date(created_at) = CURDATE()');
			$documentPatent = $documentPatent->whereRaw('Date(created_at) = CURDATE()');
			$documentPatentchange = $documentPatentchange->whereRaw('Date(created_at) = CURDATE()');
			$documentPatentrecertifying = $documentPatentrecertifying->whereRaw('Date(created_at) = CURDATE()');
		}

		if ($request->has('manager')) {
			$this->reportServices = $this->reportServices->where('operator_id' , '=', $request->get('manager'));
			$this->currectManager = $request->get('manager');
		}

		if (Auth::user()->hasRole('managermu')) {
			$this->reportServices = $this->reportServices->where('operator_id' , '=', Auth::user()->id);

			$documentDms = $documentDms->where('operator_id', Auth::user()->id);
			$documentIg = $documentIg->where('operator_id', Auth::user()->id);
			$documentPatent = $documentPatent->where('operator_id', Auth::user()->id);
			$documentPatentchange = $documentPatentchange->where('operator_id', Auth::user()->id);
			$documentPatentrecertifying = $documentPatentrecertifying->where('operator_id', Auth::user()->id);
		} else {
			$this->reportServices = $this->reportServices->whereIn('operator_id', $operatorIds);

			$documentDms = $documentDms->where('operator_id', $request->get('manager'));
			$documentIg = $documentIg->where('operator_id', $request->get('manager'));
			$documentPatent = $documentPatent->where('operator_id', $request->get('manager'));
			$documentPatentchange = $documentPatentchange->where('operator_id', $request->get('manager'));
			$documentPatentrecertifying = $documentPatentrecertifying->where('operator_id', $request->get('manager'));
		}

		$this->serviceTitles = [];
		$this->totalPrice = 0;
		$this->totalServices = 0;
		$this->totalIndividualPrice = 0;
		$this->totalLegalPrice = 0;
		$this->totalIndividualServices = 0;
		$this->totalLegalServices = 0;
		$this->totalPayment = 0;
		$this->totalDocuments = 0;

		$this->totalDocuments += $documentDms->count();
		$this->totalDocuments += $documentIg->count();
		$this->totalDocuments += $documentPatent->count();
		$this->totalDocuments += $documentPatentchange->count();
		$this->totalDocuments += $documentPatentrecertifying->count();

		$this->services = \MMC\Models\Service::where('module', '=', '1')->where('status', '=', '1')->orderBy('order', 'asc')->get();

		foreach ($this->services as $service) {
			$this->serviceTitles[$service->name] = 0;
		}

		foreach ($this->reportServices->get() as $service) {
			$this->serviceTitles[$service->service->name] = isset($this->serviceTitles[$service->service->name]) ? $this->serviceTitles[$service->service->name] + $service->service_count : 1;

			$servicePrice = $service->service_price * $service->service_count;

			$this->totalServices += $service->service_count;
			if ($service->client->type) {
				$this->totalIndividualServices += $service->service_count;
			} else {
				$this->totalLegalServices += $service->service_count;
			}

			if ($service->payment_status == 1) {
				$this->totalPrice += $servicePrice;
				$this->totalPayment += $service->service_count;

				if ($service->client->type) {
					$this->totalIndividualPrice += $servicePrice;
				} else {
					$this->totalLegalPrice += $servicePrice;
				}
			}
		}
	}

	public function index(Request $request)
	{
		if ($request->has('export')) {
			return $this->export();
		}

		return view('report.mu.index', [
			'reportServices' => $this->reportServices->get(),
			'serviceTitles' => $this->serviceTitles,
			'totalServices' => $this->totalServices,
			'totalIndividualPrice' => $this->totalIndividualPrice,
			'totalLegalPrice' => $this->totalLegalPrice,
			'totalIndividualServices' => $this->totalIndividualServices,
			'totalLegalServices' => $this->totalLegalServices,
			'operators' => $this->operators,
			'totalPayment' => $this->totalPayment,
			'clients' => $this->clients,
			'totalDocuments' => $this->totalDocuments,
			'daterange' => $this->daterange,
			'export' => false,
		]);
	}

	public function print()
	{
		return view('report.mu.print', [
			'reportServices' => $this->reportServices->get(),
			'serviceTitles' => $this->serviceTitles,
			'totalServices' => $this->totalServices,
			'totalIndividualPrice' => $this->totalIndividualPrice,
			'totalLegalPrice' => $this->totalLegalPrice,
			'totalIndividualServices' => $this->totalIndividualServices,
			'totalLegalServices' => $this->totalLegalServices,
			'operators' => $this->operators,
			'totalPayment' => $this->totalPayment,
			'clients' => $this->clients,
			'totalDocuments' => $this->totalDocuments,
			'daterange' => $this->daterange,
			'export' => false,
		]);
	}

	public function export()
	{
		if ($this->currectManager) {
			$manager = str_replace(' ', '_', User::find($this->currectManager)->name);
		} else {
			$manager = 'Все_менеджеры';
		}

		if ($this->currectClient) {
			$client = str_replace(' ', '_', Client::find($this->currectClient)->name);
		} else {
			$client = 'Все_Плательщики';
		}

		$filename = 'МУ_'.$manager.'_'.$client.'_'.$this->daterange[0].'-'.$this->daterange[1];
		$file = \Excel::create($filename, function($excel) {
		    $excel->sheet('New sheet', function($sheet) {
		        $sheet->loadView('report.mu.table', [
		        	'reportServices' => $this->reportServices->get(),
					'serviceTitles' => $this->serviceTitles,
					'totalServices' => $this->totalServices,
					'totalIndividualPrice' => $this->totalIndividualPrice,
					'totalLegalPrice' => $this->totalLegalPrice,
					'totalIndividualServices' => $this->totalIndividualServices,
					'totalLegalServices' => $this->totalLegalServices,
					'operators' => $this->operators,
					'totalPayment' => $this->totalPayment,
					'clients' => $this->clients,
					'totalDocuments' => $this->totalDocuments,
					'daterange' => $this->daterange,
					'export' => true,
		        ]);
		    });
		})->store('csv');

		$file = Storage::disk('export')->get($filename.'.csv');
		$encoded = mb_convert_encoding($file, 'Windows-1251', 'UTF-8');
		Storage::disk('export_public')->put($filename.'.csv', $encoded);

		return response()->download(public_path().'/exports/'.$filename.'.csv');
	}
}
