<?php

namespace MMC\Http\Controllers\Operator\Report;

ini_set('max_execution_time', 600);

use Illuminate\Http\Request;

use MMC\Http\Requests;
use MMC\Http\Controllers\Controller;
use Kris\LaravelFormBuilder\FormBuilder;
use Auth;
use Helper;
use Ultraware\Roles\Models\Role;
use MMC\Models\ForeignerService;
use MMC\Models\ForeignerServiceGroup;
use MMC\Models\ForeignerDms;
use MMC\Models\ForeignerIg;
use MMC\Models\ForeignerPatent;
use MMC\Models\ForeignerPatentChange;
use MMC\Models\ForeignerPatentRecertifying;
use MMC\Models\User;
use MMC\Models\MMC;
use MMC\Models\UserMMC;
use Storage;

class SummaryReportController extends Controller
{
	public $currentMMC;
	public $serviceCount;
	public $totalServices;
	public $totalPrice;
	public $servicesByOperator;
	public $daterange;
	public $users = [];

	public function __construct(Request $request)
	{
		$this->serviceCount = [];
		$this->servicePrices = [];
		$this->servicesByOperator = [];
		$this->currentMMC = null;

		$this->totalForeigners = 0;
		$this->totalPayment = 0;
		$this->totalPaymentComplex = 0;
		$this->totalPrice = 0;

		$this->totalRepayment = 0;
		$this->mmc = MMC::whereIn('id', Auth::user()->mmcListId())->get();
		$this->daterange[0] = date('d.m.y', strtotime('first day of this month'));
		$this->daterange[1] = date('d.m.y');

		$this->services = \MMC\Models\Service::orderBy('order', 'asc')->where('status', '=', '1')->get();

		foreach ($this->services as $service) {
			$this->serviceCount[$service->name] = 0;
			$this->servicePrices[$service->name] = 0;
		}

		$users = User::orderBy('name', 'asc');
		if (Auth::user()->hasRole('business.manager|accountant')) {
			$mmcUsers = UserMMC::whereIn('mmc_id', Auth::user()->mmcListId())->pluck('user_id')->toArray();
			$users = $users->where('active', '=', 1)->whereIn('id', $mmcUsers);
		} else {
			if ($request->has('mmc')) {
				$mmcUsers = UserMMC::where('mmc_id', $request->get('mmc'))->pluck('user_id')->toArray();
				$users = $users->whereIn('id', $mmcUsers)->where('active', '=', 1);
				$this->currentMMC = $request->get('mmc');
			} else {
				$mmcUsers = UserMMC::whereIn('mmc_id', Auth::user()->mmcListId())->pluck('user_id')->toArray();
				$users = User::where('active', '=', 1)->whereIn('id', $mmcUsers);
				$this->currentMMC = Auth::user()->mmcListId()[0];
			}
		}

		$roles = Role::orWhere('slug', '=', 'managertm')->orWhere('slug', '=', 'managertmsn')->orWhere('slug', '=', 'managermu')->orWhere('slug', '=', 'managermusn')->get();

		$i = 0;
		foreach ($users->get() as $user) {
			foreach ($roles as $role) {
				if ($user->hasRole($role->slug)) {
					$this->users[$i] = $user;
					$this->users[$i]->role = $role->name;
					$i++;
				}
			}
		}

		$servicesTM = ForeignerService::where('is_mu', 0);
		$servicesMU = new ForeignerServiceGroup;

		$documentDms = new ForeignerDms;
		$documentIg = new ForeignerIg;
		$documentPatent = new ForeignerPatent;
		$documentPatentchange = new ForeignerPatentChange;
		$documentPatentrecertifying = new ForeignerPatentRecertifying;

		if ($request->has('daterange')) {
			$this->daterange = explode('-', $request->get('daterange'));

			$servicesTM = $servicesTM->whereDate('created_at' , '>=', Helper::formatDateForQuery($this->daterange[0]));
			$servicesMU = $servicesMU->whereDate('created_at' , '>=', Helper::formatDateForQuery($this->daterange[0]));
			$documentDms = $documentDms->whereDate('created_at' , '>=', Helper::formatDateForQuery($this->daterange[0]));
			$documentIg = $documentIg->whereDate('created_at' , '>=', Helper::formatDateForQuery($this->daterange[0]));
			$documentPatent = $documentPatent->whereDate('created_at' , '>=', Helper::formatDateForQuery($this->daterange[0]));
			$documentPatentchange = $documentPatentchange->whereDate('created_at' , '>=', Helper::formatDateForQuery($this->daterange[0]));
			$documentPatentrecertifying = $documentPatentrecertifying->whereDate('created_at' , '>=', Helper::formatDateForQuery($this->daterange[0]));

			$servicesTM = $servicesTM->whereDate('created_at' , '<=', Helper::formatDateForQuery($this->daterange[1]));
			$servicesMU = $servicesMU->whereDate('created_at' , '<=', Helper::formatDateForQuery($this->daterange[1]));
			$documentDms = $documentDms->whereDate('created_at' , '<=', Helper::formatDateForQuery($this->daterange[1]));
			$documentIg = $documentIg->whereDate('created_at' , '<=', Helper::formatDateForQuery($this->daterange[1]));
			$documentPatent = $documentPatent->whereDate('created_at' , '<=', Helper::formatDateForQuery($this->daterange[1]));
			$documentPatentchange = $documentPatentchange->whereDate('created_at' , '<=', Helper::formatDateForQuery($this->daterange[1]));
			$documentPatentrecertifying = $documentPatentrecertifying->whereDate('created_at' , '<=', Helper::formatDateForQuery($this->daterange[1]));
		} else {
			$servicesTM = $servicesTM->whereRaw('Date(created_at) = CURDATE()');
			$servicesMU = $servicesMU->whereRaw('Date(created_at) = CURDATE()');

			$documentDms = $documentDms->whereRaw('Date(created_at) = CURDATE()');
			$documentIg = $documentIg->whereRaw('Date(created_at) = CURDATE()');
			$documentPatent = $documentPatent->whereRaw('Date(created_at) = CURDATE()');
			$documentPatentchange = $documentPatentchange->whereRaw('Date(created_at) = CURDATE()');
			$documentPatentrecertifying = $documentPatentrecertifying->whereRaw('Date(created_at) = CURDATE()');
		}

		$documentDms = $documentDms->get();
		$documentIg = $documentIg->get();
		$documentPatent = $documentPatent->get();
		$documentPatentchange = $documentPatentchange->get();
		$documentPatentrecertifying = $documentPatentrecertifying->get();

		$mergedServices = $servicesTM->get()->merge($servicesMU->get());

		$i = 0;
		foreach ($this->users as $user) {
			$this->servicesByOperator[$i]['totalPrice'] = 0;
			$this->servicesByOperator[$i]['totalServices'] = 0;
			$this->servicesByOperator[$i]['totalDocuments'] = 0;
			$this->servicesByOperator[$i]['totalRepayment'] = 0;
			$this->servicesByOperator[$i]['totalPayment'] = 0;
			$this->servicesByOperator[$i]['totalPaymentComplex'] = 0;
			$this->servicesByOperator[$i]['totalClients'] = 0;
			$this->servicesByOperator[$i]['services'] = [];
			$this->servicesByOperator[$i]['operator'] = User::find($user->id);

			$this->servicesByOperator[$i]['totalDocuments'] += $documentDms->where('operator_id', $user->id)->count();
			$this->servicesByOperator[$i]['totalDocuments'] += $documentIg->where('operator_id', $user->id)->count();
			$this->servicesByOperator[$i]['totalDocuments'] += $documentPatent->where('operator_id', $user->id)->count();
			$this->servicesByOperator[$i]['totalDocuments'] += $documentPatentchange->where('operator_id', $user->id)->count();
			$this->servicesByOperator[$i]['totalDocuments'] += $documentPatentrecertifying->where('operator_id', $user->id)->count();

			$this->servicesByOperator[$i]['operator']->role = $user['role'];

			foreach ($this->services as $service) {
				$this->servicesByOperator[$i]['services'][$service->name] = 0;
			}
			$userServices = $mergedServices->where('operator_id', $user->id);
			foreach ($userServices as $service) {
				// if ($service->service) {
					if (!isset($service->service_count)) {
						$service->service_count = 1;
					}

					$this->servicesByOperator[$i]['totalServices'] += $service->service_count;
					if ($service->repayment_status == 3) {
						$this->servicesByOperator[$i]['totalRepayment'] += $service->service_count;
					}

					if ($service->payment_status == 1 && $service->repayment_status == 0) { // только оплаченные услуги
						// Заполняем матрицу услуг по менеджерам
						$this->servicesByOperator[$i]['services'][$service->service_name] = isset($this->servicesByOperator[$i]['services'][$service->service_name]) ? $this->servicesByOperator[$i]['services'][$service->service_name] + $service->service_count : 1;
						$this->servicesByOperator[$i]['totalPrice'] += $service->service_price * $service->service_count;

						if ($service->is_complex) {
							$this->servicesByOperator[$i]['totalPaymentComplex'] += $service->service_count;
							$this->totalPaymentComplex += $service->service_count;
						} else {
							$this->servicesByOperator[$i]['totalPayment'] += $service->service_count;
							$this->totalPayment += $service->service_count;
						}

						$this->totalPrice += $service->service_count * $service->service_price;

						// Считаем сумму по оказанным услугам
						$servicePrice = $service->service_price * $service->service_count;
						$this->servicePrices[$service->service_name] = isset($this->servicePrices[$service->service_name]) ? $this->servicePrices[$service->service_name] + $servicePrice : $servicePrice;
					}
				// }
			}
			// Количество клиентов
			$this->servicesByOperator[$i]['totalClients'] = 0;
			if ($user->hasRole('managertm|managertmsn')) {
				$this->servicesByOperator[$i]['totalClients'] = $userServices->where('operator_id', $user->id)->unique('foreigner_id')->count('foreigner_id');
			} else {
				$this->servicesByOperator[$i]['totalClients'] = $userServices->where('operator_id', $user->id)->unique('application_id')->count('application_id');
			}

			$this->totalForeigners += $this->servicesByOperator[$i]['totalClients'];

			$i++;
		}

	}

	public function index(Request $request)
	{
		if ($request->has('export')) {
			return $this->export();
		}

		return view('report.summary.index', [
			'serviceCount' => $this->serviceCount,
			'totalPrice' => $this->totalPrice,
			'totalPayment' => $this->totalPayment,
			'totalPaymentComplex' => $this->totalPaymentComplex,
			'servicesByOperator' => $this->servicesByOperator,
			'servicePrices' => $this->servicePrices,
			'totalForeigners' => $this->totalForeigners,
			'mmc' => $this->mmc,
			'daterange' => $this->daterange
		]);
	}

	public function print()
	{
		return view('report.summary.print', [
			'serviceCount' => $this->serviceCount,
			'totalPrice' => $this->totalPrice,
			'totalPayment' => $this->totalPayment,
			'totalPaymentComplex' => $this->totalPaymentComplex,
			'servicesByOperator' => $this->servicesByOperator,
			'servicePrices' => $this->servicePrices,
			'totalForeigners' => $this->totalForeigners,
			'daterange' => $this->daterange
		]);
	}

	public function export()
	{
		if ($this->currentMMC) {
			$mmc = str_replace(' ', '_', MMC::find($this->currentMMC)->name);
		} else {
			$mmc = 'Все_ММЦ';
		}

		$filename = 'Отчет_Менеджеры_'.$mmc.'_'.$this->daterange[0].'-'.$this->daterange[1];
		$file = \Excel::create($filename, function($excel) {
		    $excel->sheet('New sheet', function($sheet) {
		        $sheet->loadView('report.summary.export-table', [
		        	'serviceCount' => $this->serviceCount,
					'totalPrice' => $this->totalPrice,
					'totalPayment' => $this->totalPayment,
					'totalPaymentComplex' => $this->totalPaymentComplex,
					'servicesByOperator' => $this->servicesByOperator,
					'servicePrices' => $this->servicePrices,
					'totalForeigners' => $this->totalForeigners,
					'daterange' => $this->daterange
		        ]);
		    });
		})->store('csv');

		$file = Storage::disk('export')->get($filename.'.csv');
		$encoded = mb_convert_encoding($file, 'Windows-1251', 'UTF-8');
		Storage::disk('export_public')->put($filename.'.csv', $encoded);

		return response()->download(public_path().'/exports/'.$filename.'.csv');
	}
}