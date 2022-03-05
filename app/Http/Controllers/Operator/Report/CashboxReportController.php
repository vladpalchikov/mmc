<?php

namespace MMC\Http\Controllers\Operator\Report;

use Illuminate\Http\Request;

use MMC\Http\Requests;
use MMC\Http\Controllers\Controller;
use Kris\LaravelFormBuilder\FormBuilder;
use Auth;
use Helper;
use MMC\Models\Client;
use MMC\Models\ForeignerService;
use MMC\Models\UserMMC;

class CashboxReportController extends Controller
{
	public $reportServices;
	public $totalServices;
	public $totalPrice;
	public $totalPriceAgent;
	public $totalPricePrincipal;
	public $cashiers;
	public $companies;
	public $clients;
	public $mmc;
	public $daterange;

	public function __construct(Request $request)
	{
		$this->reportServices = [];
		$this->cashierServices = ForeignerService::where('repayment_status', '<>', 3);
		$this->totalPrice = 0;
		$this->totalServices = 0;
		$this->totalPriceAgent = 0;
		$this->totalPricePrincipal = 0;
		$this->companies = \MMC\Models\Company::all();
		$this->mmc = \MMC\Models\MMC::all();
		$this->daterange[0] = date('d.m.y');
		$this->daterange[1] = date('d.m.y');

		if (Auth::user()->hasRole('business.manager|business.managerbg|accountant|cashier')) {
			$mmcUsers = UserMMC::whereIn('mmc_id', Auth::user()->mmcListId())->pluck('user_id')->toArray();
			$users = \MMC\Models\User::where('active', '=', 1)->whereIn('id', $mmcUsers)->orderBy('name', 'asc')->get();
		} else {
			$users = \MMC\Models\User::where('active', '=', 1)->orderBy('name', 'asc')->get();
		}

		foreach ($users as $user) {
			if ($user->hasRole('cashier')) {
				$this->cashiers[] = $user;
			}
		}

		foreach (Client::orderBy('name', 'asc')->get() as $client) {
			$this->clients[] = $client;
		}

		if ($request->has('client')) {
			$this->cashierServices = $this->cashierServices->where('client_id', '=', $request->get('client'));
		}

		if (Auth::user()->hasRole('business.manager|business.managerbg|accountant')) {
			$mmcUsers = UserMMC::whereIn('mmc_id', Auth::user()->mmcListId())->pluck('user_id')->toArray();
			$operatorIds = $mmcUsers;
			$this->cashierServices = $this->cashierServices->whereIn('operator_id', $operatorIds);
		}

		if ($request->has('mmc')) {
			$mmcUsers = UserMMC::where('mmc_id', $request->get('mmc'))->pluck('user_id')->toArray();
			$operatorIds = $mmcUsers;
			$this->cashierServices = $this->cashierServices->whereIn('operator_id', $operatorIds);
		}

		if ($request->has('daterange')) {
			$this->daterange = explode('-', $request->get('daterange'));
			if (count($this->daterange) == 1) {
				$this->cashierServices = $this->cashierServices->whereDate('payment_at', '=', Helper::formatDateForQuery($this->daterange[0]));
			} else {
				$this->cashierServices = $this->cashierServices->whereDate('payment_at', '>=', Helper::formatDateForQuery($this->daterange[0]));

				$this->cashierServices = $this->cashierServices->whereDate('payment_at', '<=', Helper::formatDateForQuery($this->daterange[1]));
			}
		} else {
			$this->cashierServices = $this->cashierServices->whereDate('payment_at', '=', \Carbon\Carbon::today()->toDateString());
		}

		if ($request->has('cashier')) {
			$this->cashierServices = $this->cashierServices->where('cashier_id' , '=', $request->get('cashier'));
		} else {
			if (Auth::user()->hasRole('cashier') && !Auth::user()->hasRole('administrator')) {
				$this->cashierServices = $this->cashierServices->where('cashier_id' , '=', Auth::user()->id);
			}
		}

		$services = \MMC\Models\Service::orderBy('order', 'asc');
		if ($request->has('company')) {
			$companyServices = \MMC\Models\Service::where('company_id', '=', $request->get('company'))->pluck('id')->toArray();
			$this->cashierServices = $this->cashierServices->whereIn('service_id' , $companyServices);
			$services = $services->where('company_id', '=', $request->get('company'));
		}

		foreach ($services->get() as $service) {
			$this->reportServices[$service->name]['price'] = $service->price;
			$this->reportServices[$service->name]['count'] = 0;
			$this->reportServices[$service->name]['agent_compensation'] = 0;
			$this->reportServices[$service->name]['principal_sum'] = 0;
			$this->reportServices[$service->name]['total_price'] = 0;
			$this->reportServices[$service->name]['total_agent_compensation'] = 0;
			$this->reportServices[$service->name]['total_principal_sum'] = 0;
			if (\MMC\Models\Company::find($service->company_id)) {
				$this->reportServices[$service->name]['company'] = \MMC\Models\Company::find($service->company_id)->name;
			} else {
				$this->reportServices[$service->name]['company'] = '—';
			}
		}
		// dd($this->cashierServices->count());
		$cashierServices = $this->cashierServices->get();
		foreach ($cashierServices as $cashierService) {
			$this->reportServices[$cashierService->service->name]['price'] = $cashierService->service_price;
			if (isset($this->reportServices[$cashierService->service->name]['count'])) {
				$this->reportServices[$cashierService->service->name]['count']++;
			} else {
				$this->reportServices[$cashierService->service->name]['count'] = 0;
			}

			if (isset($this->reportServices[$cashierService->service->name]['total_price'])) {
				$this->reportServices[$cashierService->service->name]['total_price'] += $cashierService->service_price;
			} else {
				$this->reportServices[$cashierService->service->name]['total_price'] = 0;
			}

			if (isset($this->reportServices[$cashierService->service->name]['total_agent_compensation'])) {
				$this->reportServices[$cashierService->service->name]['total_agent_compensation'] += $cashierService->service_agent_compensation;
			} else {
				$this->reportServices[$cashierService->service->name]['total_agent_compensation'] = 0;
			}

			if (isset($this->reportServices[$cashierService->service->name]['total_principal_sum'])) {
				$this->reportServices[$cashierService->service->name]['total_principal_sum'] += $cashierService->service_principal_sum;
			} else {
				$this->reportServices[$cashierService->service->name]['total_principal_sum'] = 0;
			}

			if (\MMC\Models\Company::find($cashierService->service->company_id)) {
				$this->reportServices[$cashierService->service->name]['company'] = \MMC\Models\Company::find($cashierService->service->company_id)->name;
			} else {
				$this->reportServices[$cashierService->service->name]['company'] = '—';
			}

			$this->reportServices[$cashierService->service->name]['agent_compensation'] = $cashierService->service_agent_compensation;
			$this->reportServices[$cashierService->service->name]['principal_sum'] = $cashierService->service_principal_sum;
			$this->totalPrice += $cashierService->service_price;
			$this->totalPriceAgent += $cashierService->service_agent_compensation;
			$this->totalPricePrincipal += $cashierService->service_principal_sum;
			$this->totalServices++;
		}
	}

	public function index()
	{
		return view('report.cashbox.index', [
			'cashiers' => $this->cashiers,
			'reportServices' => $this->reportServices,
			'totalPrice' => $this->totalPrice,
			'totalPriceAgent' => $this->totalPriceAgent,
			'totalPricePrincipal' => $this->totalPricePrincipal,
			'totalServices' => $this->totalServices,
			'companies' => $this->companies,
			'clients' => $this->clients,
			'mmc' => $this->mmc,
			'daterange' => $this->daterange
		]);
	}

	public function print()
	{
		return view('report.cashbox.print', [
			'cashiers' => $this->cashiers,
			'reportServices' => $this->reportServices,
			'totalPrice' => $this->totalPrice,
			'totalPriceAgent' => $this->totalPriceAgent,
			'totalPricePrincipal' => $this->totalPricePrincipal,
			'totalServices' => $this->totalServices,
			'companies' => $this->companies,
			'clients' => $this->clients,
			'mmc' => $this->mmc,
			'daterange' => $this->daterange
		]);
	}
}
