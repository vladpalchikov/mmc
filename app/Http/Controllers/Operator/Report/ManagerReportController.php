<?php

namespace MMC\Http\Controllers\Operator\Report;

ini_set('max_execution_time', 600);

use Illuminate\Http\Request;

use MMC\Http\Requests;
use MMC\Http\Controllers\Controller;
use Auth;
use Helper;
use MMC\Models\User;
use MMC\Models\MMC;
use MMC\Models\Service;
use MMC\Models\UserMMC;
use MMC\Models\Foreigner;
use MMC\Models\ForeignerService;
use MMC\Models\ForeignerDms;
use MMC\Models\ForeignerIg;
use MMC\Models\ForeignerPatent;
use MMC\Models\ForeignerPatentChange;
use MMC\Models\ForeignerPatentRecertifying;
use DB;

class ManagerReportController extends Controller
{
	public $operators;
	public $daterange;
	public $mmc;
	public $managerServices;
	public $users = [];
	public $services;
	public $currentMMC;
	public $totalStats = [];

	public function __construct(Request $request)
	{
		if (!Auth::check()) {
			return redirect('/');
		}

		$this->daterange[0] = date('d.m.y');
		$this->daterange[1] = date('d.m.y');
		$this->mmc = MMC::whereIn('id', Auth::user()->mmcListId())->get();

		if ($request->has('daterange')) {
			$this->daterange = explode('-', $request->get('daterange'));
		}

		$users = User::orderBy('name', 'asc');
		if (Auth::user()->hasRole('business.manager|business.managerbg|accountant')) {
			$mmcUsers = UserMMC::whereIn('mmc_id', Auth::user()->mmcListId())->pluck('user_id')->toArray();
			$users = $users->whereIn('id', $mmcUsers);
			$this->currentMMC = Auth::user()->mmcListId()[0];
		} elseif (Auth::user()->hasRole('administrator|chief.accountant')) {
			if ($request->has('mmc')) {
				$mmcUsers = UserMMC::where('mmc_id', $request->get('mmc'))->pluck('user_id')->toArray();
				$users = $users->whereIn('id', $mmcUsers);
				$this->currentMMC = $request->get('mmc');
			} else {
				$users = $users->where('mmc_id', MMC::first()->id);
				$this->currentMMC = MMC::first()->id;
			}
 		}

 		$usersIds = [];

 		$users = $users->get();
 		foreach ($users as $user) {
 			if ($user->hasRole('managertmsn|managertm|managermu|managermusn|managerbg')) {
				$this->users[$user->id] = $user->toArray();
				$usersIds[] = $user->id;
 			}
 		}

 		$services = Service::orderBy('order', 'asc')->get();

 		$servicesIds = [];
 		foreach ($services as $service) {
			$this->services[$service->id] = $service->toArray();
			$servicesIds[] = $service->id;
		}

 		$managerServices = [];

 		$foreigner = new Foreigner;
 		$documentDms = new ForeignerDms;
		$documentIg = new ForeignerIg;
		$documentPatent = new ForeignerPatent;
		$documentPatentchange = new ForeignerPatentChange;
		$documentPatentrecertifying = new ForeignerPatentRecertifying;
		if ($request->has('daterange')) {
			if (count($this->daterange) == 1) {
				$foreigner = $foreigner->whereDate('created_at' , '=', Helper::formatDateForQuery($this->daterange[0]));

				$documentDms = $documentDms->whereDate('created_at' , '=', Helper::formatDateForQuery($this->daterange[0]));
				$documentIg = $documentIg->whereDate('created_at' , '=', Helper::formatDateForQuery($this->daterange[0]));
				$documentPatent = $documentPatent->whereDate('created_at' , '=', Helper::formatDateForQuery($this->daterange[0]));
				$documentPatentchange = $documentPatentchange->whereDate('created_at' , '=', Helper::formatDateForQuery($this->daterange[0]));
				$documentPatentrecertifying = $documentPatentrecertifying->whereDate('created_at' , '=', Helper::formatDateForQuery($this->daterange[0]));
			} else {
				$foreigner = $foreigner->whereDate('created_at' , '>=', Helper::formatDateForQuery($this->daterange[0]));
				$foreigner = $foreigner->whereDate('created_at' , '<=', Helper::formatDateForQuery($this->daterange[1]));

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
			$foreigner = $foreigner->whereRaw('Date(created_at) = CURDATE()');
			$documentDms = $documentDms->whereRaw('Date(created_at) = CURDATE()');
			$documentIg = $documentIg->whereRaw('Date(created_at) = CURDATE()');
			$documentPatent = $documentPatent->whereRaw('Date(created_at) = CURDATE()');
			$documentPatentchange = $documentPatentchange->whereRaw('Date(created_at) = CURDATE()');
			$documentPatentrecertifying = $documentPatentrecertifying->whereRaw('Date(created_at) = CURDATE()');
		}

		$mergedDocuments = collect();
		$mergedDocuments = $mergedDocuments->merge($documentDms->get());
		$mergedDocuments = $mergedDocuments->merge($documentIg->get());
		$mergedDocuments = $mergedDocuments->merge($documentPatent->get());
		$mergedDocuments = $mergedDocuments->merge($documentPatentchange->get());
		$mergedDocuments = $mergedDocuments->merge($documentPatentrecertifying->get());

		$this->totalStats['count_services_by_user'] = [];
		$this->totalStats['count_services_by_users_total'] = 0;
		$this->totalStats['count_payed_cash'] = [];
		$this->totalStats['count_payed_cash_total'] = 0;
		$this->totalStats['count_payed_cashless'] = [];
		$this->totalStats['count_payed_cashless_total'] = 0;
		$this->totalStats['count_repayments'] = [];
		$this->totalStats['count_repayments_total'] = 0;
		$this->totalStats['count_clients'] = [];
		$this->totalStats['count_clients_total'] = 0;
		$this->totalStats['count_new_clients_total'] = 0;
		$this->totalStats['count_all_services_by_user'] = [];
		$this->totalStats['count_all_services_by_users_total'] = 0;
		$this->totalStats['count_documents'] = [];
		$this->totalStats['count_documents_total'] = 0;
		$this->totalStats['paid'] = [];
		$this->totalStats['paid_total'] = 0;
		$this->totalStats['paid_cash'] = [];
		$this->totalStats['paid_cash_total'] = 0;
		$this->totalStats['paid_cashless'] = [];
		$this->totalStats['paid_cashless_total'] = 0;

		foreach (ForeignerService::byDate($this->daterange[0], $this->daterange[1])->whereIn('operator_id', $usersIds)->get() as $foreignerService) {
			if ($foreignerService->payment_status == 1 && !$foreignerService->repayment_status) {
				if (isset($managerServices[$foreignerService->service_id][$foreignerService->operator_id])) {
					$managerServices[$foreignerService->service_id][$foreignerService->operator_id]++;
				} else {
					$managerServices[$foreignerService->service_id][$foreignerService->operator_id] = 1;
				}
			}
		}

		foreach ($users as $operator) {
			// Количество возвратов
			$this->totalStats['count_repayments'][$operator->id] = ForeignerService::byDate($this->daterange[0], $this->daterange[1])->repaymentsByOperator($operator->id)->count();
			$this->totalStats['count_repayments_total'] += $this->totalStats['count_repayments'][$operator->id];

			// Всего услуг
			$this->totalStats['count_all_services_by_user'][$operator->id] = ForeignerService::byDate($this->daterange[0], $this->daterange[1])->servicesByOperator($operator->id)->count();
			$this->totalStats['count_all_services_by_users_total'] += $this->totalStats['count_all_services_by_user'][$operator->id];

			// Количество оплаченных услуг
			$this->totalStats['count_services_by_user'][$operator->id] = ForeignerService::byDate($this->daterange[0], $this->daterange[1])->payedServicesByOperator($operator->id)->count();
			$this->totalStats['count_services_by_users_total'] += $this->totalStats['count_services_by_user'][$operator->id];

			// Количество оплаченных услуг за наличные
			$this->totalStats['count_payed_cash'][$operator->id] = ForeignerService::byDate($this->daterange[0], $this->daterange[1])->paymentCash($operator->id)->count();
			$this->totalStats['count_payed_cash_total'] += $this->totalStats['count_payed_cash'][$operator->id];

			// Количество оплаченных услуг за безнал
			$this->totalStats['count_payed_cashless'][$operator->id] = ForeignerService::byDate($this->daterange[0], $this->daterange[1])->paymentCashless($operator->id)->count();
			$this->totalStats['count_payed_cashless_total'] += $this->totalStats['count_payed_cashless'][$operator->id];

			// Итого оплачено
			$this->totalStats['paid'][$operator->id] = ForeignerService::byDate($this->daterange[0], $this->daterange[1])->totalPayed($operator->id)->sum('service_price');
			$this->totalStats['paid_total'] += $this->totalStats['paid'][$operator->id];

			// Итого оплачено за наличные
			$this->totalStats['paid_cash'][$operator->id] = ForeignerService::byDate($this->daterange[0], $this->daterange[1])->paymentCash($operator->id)->sum('service_price');
			$this->totalStats['paid_cash_total'] += $this->totalStats['paid_cash'][$operator->id];

			// Итого оплачено за безнал
			$this->totalStats['paid_cashless'][$operator->id] = ForeignerService::byDate($this->daterange[0], $this->daterange[1])->paymentCashLess($operator->id)->sum('service_price');
			$this->totalStats['paid_cashless_total'] += $this->totalStats['paid_cashless'][$operator->id];

			// Количество клиентов
			$this->totalStats['count_clients'][$operator->id] = ForeignerService::byDate($this->daterange[0], $this->daterange[1])->where('operator_id', $operator->id)->where('is_mu', 0)->get()->unique('foreigner_id')->count();
			$this->totalStats['count_clients_total'] += $this->totalStats['count_clients'][$operator->id];

			// Количество документов
			$this->totalStats['count_documents'][$operator->id] = $mergedDocuments->where('operator_id', $operator->id)->count();
			$this->totalStats['count_documents_total'] += $this->totalStats['count_documents'][$operator->id];
		}

		$this->totalStats['count_new_clients_total'] += Foreigner::byDate($this->daterange[0], $this->daterange[1])->whereIn('id', ForeignerService::byDate($this->daterange[0], $this->daterange[1])->whereIn('operator_id', $usersIds)->where('is_mu', 0)->pluck('foreigner_id')->toArray())->count();

		foreach ($this->services as $service_id => $service) {
			if (!isset($managerServices[$service_id])) {
				unset($this->services[$service_id]);
			}
		}

		foreach ($this->users as $user) {
			if ($this->totalStats['count_services_by_user'][$user['id']] == 0 && $user['active'] == 0) {
				unset($this->users[$user['id']]);
			}
		}

		$this->managerServices = $managerServices;
	}

	public function index()
	{
		return view('report.manager.index', [
			'mmc' => $this->mmc,
			'operators' => $this->operators,
			'managerServices' => $this->managerServices,
			'users' => $this->users,
			'services' => $this->services,
			'totalStats' => $this->totalStats,
			'currentMMC' => $this->currentMMC,
			'daterange' => $this->daterange
		]);
	}

	public function print()
	{
		return view('report.manager.print', [
			'mmc' => $this->mmc,
			'operators' => $this->operators,
			'managerServices' => $this->managerServices,
			'users' => $this->users,
			'services' => $this->services,
			'totalStats' => $this->totalStats,
			'currentMMC' => $this->currentMMC,
			'daterange' => $this->daterange
		]);
	}
}
