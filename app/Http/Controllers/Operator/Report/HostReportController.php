<?php

namespace MMC\Http\Controllers\Operator\Report;

use Illuminate\Http\Request;

use MMC\Http\Requests;
use MMC\Http\Controllers\Controller;
use Auth;
use Helper;
use MMC\Models\User;
use MMC\Models\Client;
use MMC\Models\Service;
use MMC\Models\UserMMC;
use MMC\Models\Foreigner;
use MMC\Models\ForeignerReg;
use MMC\Models\ForeignerService;
use MMC\Models\ForeignerDms;
use MMC\Models\ForeignerIg;
use MMC\Models\ForeignerPatent;
use MMC\Models\ForeignerPatentChange;
use MMC\Models\ForeignerPatentRecertifying;
use Storage;

class HostReportController extends Controller
{
	public $hosts;
	public $clients;
	public $operators;
	public $daterange;
	public $currectManager;
	public $currectClient;

	public function __construct(Request $request)
	{
		$this->daterange[0] = date('d.m.y');
		$this->daterange[1] = date('d.m.y');
		$this->clients = Client::orderBy('name')->get();

		if (Auth::user()->hasRole('business.manager|managertmsn|accountant')) {
			$mmcUsers = UserMMC::whereIn('mmc_id', Auth::user()->mmcListId())->pluck('user_id')->toArray();
			foreach (\MMC\Models\User::where('active', '=', 1)->whereIn('id', $mmcUsers)->orderBy('name', 'asc')->get() as $user) {
				if ($user->hasRole('managertmsn|managertm')) {
					$this->operators[] = $user;
				}
			}
		} else {
			foreach (\MMC\Models\User::where('active', '=', 1)->orderBy('name', 'asc')->get() as $user) {
				if ($user->hasRole('managertmsn|managertm')) {
					$this->operators[] = $user;
				}
			}
		}

		if (!$request->has('client')) {
			return false;
		}

		$this->hosts = ForeignerReg::orderBy('created_at');

		if ($request->has('daterange')) {
			$this->daterange = explode('-', $request->get('daterange'));
			if (count($this->daterange) == 1) {
				$this->hosts = $this->hosts->whereDate('created_at' , '=', Helper::formatDateForQuery($this->daterange[0]));
			} else {
				$this->hosts = $this->hosts->whereDate('created_at' , '>=', Helper::formatDateForQuery($this->daterange[0]));
				$this->hosts = $this->hosts->whereDate('created_at' , '<=', Helper::formatDateForQuery($this->daterange[1]));
			}
		} else {
			$this->hosts = $this->hosts->whereRaw('Date(created_at) = CURDATE()');
		}

		if ($request->has('manager')) {
			$this->hosts = $this->hosts->where('operator_id' , '=', $request->get('manager'));
			$this->currectManager = $request->get('manager');
		} else {
			// $this->hosts = $this->hosts->where('operator_id' , '=', Auth::user()->id);
		}

		if ($request->has('client')) {
			$this->hosts = $this->hosts->where('client_id' , '=', $request->get('client'));
			$this->currectClient = $request->get('client');
		}

		$this->hosts = $this->hosts->get();
	}

	public function index(Request $request)
	{
		if ($request->has('export')) {
			return $this->export();
		}

		return view('report.host.index', [
			'hosts' => $this->hosts,
			'operators' => $this->operators,
			'daterange' => $this->daterange,
			'clients' => $this->clients,
		]);
	}

	public function print()
	{
		return view('report.host.print', [
			'hosts' => $this->hosts,
			'operators' => $this->operators,
			'daterange' => $this->daterange,
			'clients' => $this->clients,
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

		$filename = 'Регистрация_ИГ_'.$manager.'_'.$client.'_'.$this->daterange[0].'-'.$this->daterange[1];
		$file = \Excel::create($filename, function($excel) {
		    $excel->sheet('New sheet', function($sheet) {
		        $sheet->loadView('report.host.table', [
		        	'hosts' => $this->hosts,
		        ]);
		    });
		})->store('csv');

		$file = Storage::disk('export')->get($filename.'.csv');
		$encoded = mb_convert_encoding($file, 'Windows-1251', 'UTF-8');
		Storage::disk('export_public')->put($filename.'.csv', $encoded);

		return response()->download(public_path().'/exports/'.$filename.'.csv');
	}
}