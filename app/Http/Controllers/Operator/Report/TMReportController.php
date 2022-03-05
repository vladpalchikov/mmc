<?php

namespace MMC\Http\Controllers\Operator\Report;

use Illuminate\Http\Request;

use MMC\Http\Requests;
use MMC\Http\Controllers\Controller;
use Auth;
use Helper;
use MMC\Models\Service;
use MMC\Models\UserMMC;
use MMC\Models\ForeignerService;
use MMC\Models\ForeignerDms;
use MMC\Models\ForeignerIg;
use MMC\Models\ForeignerPatent;
use MMC\Models\ForeignerPatentChange;
use MMC\Models\ForeignerPatentRecertifying;

class TMReportController extends Controller
{
	public $serviceTitles;
	public $totalServices;
	public $totalPayment;
	public $totalPrice;
	public $totalDocuments;
	public $operators;
	public $daterange;

	public function __construct(Request $request)
	{
		$this->reportServices = ForeignerService::where('is_mu', 0);
		$this->daterange[0] = date('d.m.y');
		$this->daterange[1] = date('d.m.y');

		$documentDms = new ForeignerDms;
		$documentIg = new ForeignerIg;
		$documentPatent = new ForeignerPatent;
		$documentPatentchange = new ForeignerPatentChange;
		$documentPatentrecertifying = new ForeignerPatentRecertifying;

		if (Auth::user()->hasRole('business.manager|business.managerbg|managertmsn|accountant')) {
			$mmcUsers = UserMMC::whereIn('mmc_id', Auth::user()->mmcListId())->pluck('user_id')->toArray();
			foreach (\MMC\Models\User::where('active', '=', 1)->whereIn('id', $mmcUsers)->orderBy('name', 'asc')->get() as $user) {
				if ($user->hasRole('managertmsn|managertm|managerbg')) {
					$this->operators[] = $user;
				}
			}
		} else {
			foreach (\MMC\Models\User::where('active', '=', 1)->orderBy('name', 'asc')->get() as $user) {
				if ($user->hasRole('managertmsn|managertm|managerbg')) {
					$this->operators[] = $user;
				}
			}
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
			$this->reportServices = $this->reportServices->whereRaw('Date(created_at) = CURDATE()');

			$documentDms = $documentDms->whereRaw('Date(created_at) = CURDATE()');
			$documentIg = $documentIg->whereRaw('Date(created_at) = CURDATE()');
			$documentPatent = $documentPatent->whereRaw('Date(created_at) = CURDATE()');
			$documentPatentchange = $documentPatentchange->whereRaw('Date(created_at) = CURDATE()');
			$documentPatentrecertifying = $documentPatentrecertifying->whereRaw('Date(created_at) = CURDATE()');
		}

		if ($request->has('manager')) {
			$this->reportServices = $this->reportServices->where('operator_id' , '=', $request->get('manager'));

			$documentDms = $documentDms->where('operator_id', $request->get('manager'));
			$documentIg = $documentIg->where('operator_id', $request->get('manager'));
			$documentPatent = $documentPatent->where('operator_id', $request->get('manager'));
			$documentPatentchange = $documentPatentchange->where('operator_id', $request->get('manager'));
			$documentPatentrecertifying = $documentPatentrecertifying->where('operator_id', $request->get('manager'));
		} else {
			$this->reportServices = $this->reportServices->where('operator_id' , '=', Auth::user()->id);

			$documentDms = $documentDms->where('operator_id', Auth::user()->id);
			$documentIg = $documentIg->where('operator_id', Auth::user()->id);
			$documentPatent = $documentPatent->where('operator_id', Auth::user()->id);
			$documentPatentchange = $documentPatentchange->where('operator_id', Auth::user()->id);
			$documentPatentrecertifying = $documentPatentrecertifying->where('operator_id', Auth::user()->id);
		}

		$this->serviceTitles = [];
		$this->totalPrice = 0;
		$this->totalServices = 0;
		$this->totalPayment = 0;
		$this->totalDocuments = 0;

		$this->totalDocuments += $documentDms->count();
		$this->totalDocuments += $documentIg->count();
		$this->totalDocuments += $documentPatent->count();
		$this->totalDocuments += $documentPatentchange->count();
		$this->totalDocuments += $documentPatentrecertifying->count();

		$this->services = Service::where('module', '=', '0')->where('status', '=', '1')->orderBy('order', 'asc')->get();

		foreach ($this->services as $service) {
			$this->serviceTitles[$service->name] = 0;
		}

		foreach ($this->reportServices->get() as $service) {
			$this->serviceTitles[$service->service->name] = isset($this->serviceTitles[$service->service->name]) ? $this->serviceTitles[$service->service->name] + 1 : 1;
			$this->totalServices++;
			if ($service->payment_status == 1 && $service->repayment_status == 0) {
				$this->totalPrice += $service->service_price;
				$this->totalPayment++;
			}
		}

	}

	public function index()
	{
		return view('report.tm.index', [
			'reportServices' => $this->reportServices->get(),
			'serviceTitles' => $this->serviceTitles,
			'totalPrice' => $this->totalPrice,
			'totalServices' => $this->totalServices,
			'totalPayment' => $this->totalPayment,
			'operators' => $this->operators,
			'daterange' => $this->daterange,
			'totalDocuments' => $this->totalDocuments
		]);
	}

	public function print()
	{
		return view('report.tm.print', [
			'reportServices' => $this->reportServices->get(),
			'serviceTitles' => $this->serviceTitles,
			'totalPrice' => $this->totalPrice,
			'totalServices' => $this->totalServices,
			'totalPayment' => $this->totalPayment,
			'operators' => $this->operators,
			'daterange' => $this->daterange,
			'totalDocuments' => $this->totalDocuments
		]);
	}
}
